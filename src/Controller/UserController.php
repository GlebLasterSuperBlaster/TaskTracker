<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use App\Services\TokenRandomizeService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $dm;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->dm = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/register", name="register")
     * @Template()
     * @param Request $request
     * @param TokenRandomizeService $token
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request, TokenRandomizeService $token)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('register-user', $submittedToken) && $form->isSubmitted() && $form->isValid()) {
            $user->setLastName($request->request->get('user')['lastName']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $request->request->get('user')['password']['first'];
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setName($request->request->get('user')['name']);
            $user->setRoles(['ROLE_USER']);
            $user->setToken($token->generateToken());
            $this->dm->persist($user);
            $this->dm->flush();

            $this->loginUserAutomatically($user, $password);
            return $this->redirectToRoute('main_page');
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function loginUserAutomatically($user, string $password)
    {
        $token = new UsernamePasswordToken(
            $user, $password, 'main', $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @Route("/profile", name="profile")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function profileAction(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('main_page');
        }

        $projects = $this->dm->getRepository(Project::class)->findAllByUser($user->getId());
        $errors = [];


        if ($request->getMethod() == "POST") {

            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('user-profile', $submittedToken)) {
                $user->setName($request->request->get('user')['name']);
                $user->setLastName($request->request->get('user')['lastName']);
                $password = $request->request->get('user')['currentPassword'];

                if (empty($request->request->get('user')['currentPassword']) || !$this->passwordEncoder->isPasswordValid($user, $password)) {
                    $errors['notValidCurrentPassword'] = 'Current password is wrong';
                }

                if (!empty($request->request->get('user')['password']['first']) || !empty($request->request->get('user')['password']['second'])) {
                    if ($request->request->get('user')['password']['first'] === $request->request->get('user')['password']['second']) {
                        $password = $request->request->get('user')['password']['first'];
                        $user->setPassword($password);
                    } else {
                        $errors['notEqualPassword'] = 'Fields password and confirm password are not equal';
                    }
                }

                $errorsAutoValid = $this->validator->validate($user);
                /**
                 * @var ConstraintViolationList $errorsAutoValid
                 */
                if (!is_null($errorsAutoValid)) {
                    foreach ($errorsAutoValid->getIterator() as $error) {
                        $errors[$error->getPropertyPath()] = $error->getMessage();
                    }
                }
                if ($errors === []) {
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                    $this->dm->persist($user);
                    $this->dm->flush();
                }
            }
        }

        return [
            'user' => $user,
            'errors' => $errors,
            'projects' => $projects
        ];
    }

    /**
     * @Template()
     * @Route("/restore_password/{token}", name="restore_password")
     */
    public function restorePassword(string $token, Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->dm->getRepository(User::class)->findOneBy(['token' => $token]);

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('change-password', $submittedToken) && $form->isSubmitted() && $form->isValid()) {
            $password = $request->request->get('change_password')['password']['first'];
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $this->dm->persist($user);
            $this->dm->flush();

            $this->loginUserAutomatically($user, $password);
            return $this->redirectToRoute('main_page');
        }
        return [
            'form' => $form->createView()
        ];

    }

    /**
     * @Route("/forgot_password", name="forgot_password")
     * @Template()
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer)
    {
        if ($request->getMethod() == "POST") {
            $submittedToken = $request->request->get('token');
            dump($submittedToken);
            if ($this->isCsrfTokenValid('forgot_password', $submittedToken)) {
                $email = $request->request->get('user_email');
                /**
                 * @var User $user
                 */
                $user = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user) {
                    $url = $this->generateUrl('restore_password',
                        ['token' => $user->getToken()],
                        UrlGeneratorInterface::ABSOLUTE_URL);
                    $message = (new \Swift_Message('Restore the password'))
                        ->setFrom('gb.tasktracker@gmail.com')
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                            // templates/emails/registration.html.twig
                                'email/restore_password.html.twig',
                                ['url' => $url,
                                    'user' => $user]
                            ),
                            'text/html'
                        );
                    $mailer->send($message);
                    $this->addFlash(
                        'success',
                        'The email with password restore instruction has benn sent to ' . $email
                    );
                    return $this->redirectToRoute('main_page');
                    } else {
                    $this->addFlash(
                        'warning',
                        'User with the email ' . $email . ' does not exist!'
                    );
                    return $this->redirectToRoute('forgot_password');
                }


            }
        }



    }
}
