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
     * @Route("/project", name="project_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('main_page');
        }

        $userProjects = [];

        $projectsInvitedTo = $user->getProjectsInvitedTo();
        foreach ($projectsInvitedTo as $project){
            array_push($userProjects, $project);
        }

        $projectCreatedByUser = $user->getCreatedProjects();
        foreach ($projectCreatedByUser as $project){
            array_push($userProjects, $project);
        }

        dump($userProjects);

        return $this->render('project/index.html.twig', [
            'projects' => $userProjects,
        ]);
    }

    /**
     * @Route("/project/create", name="project_create")
     */
    public function createAction(TokenRandomizeService $token)
    {

        dump($token);
        return $this->render('project/task-project.html.twig', [
            'controller_name' => 'ProjectController',
            'token' => $token,
        ]);
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
