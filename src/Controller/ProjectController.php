<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Services\TokenRandomizeService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProjectController
 * @package App\Controller
 * @Route("/project")
 */
class ProjectController extends AbstractController
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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, UrlGeneratorInterface $urlGenerator)
    {
        $this->dm = $manager;
        $this->validator = $validator;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @Route("/create", name="project_create")
     * @Template()
     * @param Request $request
     * @param TokenRandomizeService $token
     */
    public function createAction(Request $request, TokenRandomizeService $token)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('main_page');
        }
        $errors = [];
        $primaryProject = null;

        if ($request->getMethod() == "POST") {
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('project-create', $submittedToken)) {
                $primaryProject = $request->request->get('project') ?? null;
                /**
                 * @var Project $project
                 */
                $project = new Project();
                $project->setCreatedBy($user);
                $project->setTitle($request->request->get('project')['title']);
                $project->setDescription($request->request->get('project')['description']);
                $project->setToken($token->generateToken());
                if (isset($request->request->get('project')['user']) && $userEmails = $request->request->get('project')['user'])
                {
                    foreach ($userEmails as $email)
                    {
                        /**
                         * @var User $invitedUser
                         */
                        if ($invitedUser = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]))
                        {
                            if ($invitedUser === $user) {
                                $errors['inviteCreator'] = "The project creator cannot be invited to the project";
                            }
                            $project->addInvitedUser($invitedUser);
                        } else {
                            $errors['emailErrors'] = $errors['emailErrors'] ?? 'User with email ';
                            $errors['emailErrors'] .= "$email, ";
                        }
                    }
                    if (isset($errors['emailErrors'])){
                        $errors['emailErrors'] = mb_substr($errors['emailErrors'], 0, -2);
                        $errors['emailErrors'] .= " could not be found in the database";
                    }
                }

                $errorsAutoValid = $this->validator->validate($project);
                /**
                 * @var ConstraintViolationList $errorsAutoValid
                 */
                if (!is_null($errorsAutoValid)) {
                    foreach ($errorsAutoValid->getIterator() as $error) {
                        $errors[$error->getPropertyPath()] = $error->getMessage();
                    }
                }
                if ($errors === []) {

                    $this->dm->persist($project);
                    $this->dm->flush();
                    $primaryProject = null;

                    return $this->redirectToRoute("main_page");
                }

            }
        }
        return [
            'errors' => $errors,
            'project' => $primaryProject
        ];
    }

    /**
     * @Route("/view/{id}", name="project_view")
     * @param Project $project
     * @Template()
     * @return array
     */
    public function  viewAction(Project $project)
    {
        $url = $this->generateUrl('join',
            ['id' => $project->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL);

        return [
            'project' => $project,
            'inviteUrl' => $url
        ];
    }

    /**
     * @Route("/join/{id}", name="join")
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function inviteUser(Project $project)
    {
        if(!$this->getUser()) {
            $this->addFlash(
                'warning',
                'You need to log in firstly to join the project');
            return $this->redirectToRoute("main_page");
        }
        if ($project->getCreatedBy() !== $this->getUser())
        {
            $project->addInvitedUser($this->getUser());
            $this->dm->persist($project);
            $this->dm->flush();
            $this->addFlash(
                'success',
                'You have joined the project!'
            );
        }
        return $this->redirectToRoute('project_view', ['id' => $project->getId()]);
    }

    /**
     * @Route("/{sortBy}/{page}", name="project_index", defaults={"page" : 1, "sortBy" : "all"})
     * @Template()
     * @param $page
     * @param null|string $sortBy
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction($page, $sortBy)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('main_page');
        }
        $projects = $this->dm->getRepository(Project::class)->findAllSorted($user->getId(), $page, $sortBy);

        return [
            'projects' => $projects,
        ];
    }


}
