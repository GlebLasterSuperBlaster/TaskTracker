<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Services\TokenRandomizeService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $dm;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->dm = $manager;
    }

    /**
     * @Route("/project/{sortBy}/{page}", name="project_index", defaults={"page" : 1, "sortBy" : "null"})
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

    /**
     * @Route("/project/create", name="project_create")
     * @param TokenRandomizeService $token
     * @return array
     */
    public function createAction(TokenRandomizeService $token)
    {

        dump($token);
        return [
            'controller_name' => 'ProjectController',
            'token' => $token,
        ];
    }

    /**
     * @Route("/project/forme", name="project_forme")
     */
    public function formeAction()
    {
        return $this->render('project/dashboard-forme.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

}
