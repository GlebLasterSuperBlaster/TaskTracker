<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 * @package App\Controller
 * @Route("/task")
 */
class TaskController extends AbstractController
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
     * @Route("/", name="task")
     */
    public function index()
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/create/{id}", name="task_create")
     * @Template()
     * @param Request $request
     * @param Project $project
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, Project $project)
    {
        if($project->getCreatedBy() !== $this->getUser())
        {
            $this->redirectToRoute('main_page');
        }
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('task-create', $submittedToken) && $form->isSubmitted() && $form->isValid()) {
            $task->setTitle($request->request->get('task')['title']);
            $task->setDescription($request->request->get('task')['description']);
            $task->setCreatedBy($this->getUser());
            $task->setStatus($this->dm->getRepository(TaskStatus::class)->findOneBy(['title' => TaskStatus::NEW]));
            $project->addTask($task);
            $this->dm->persist($task);
            $this->dm->persist($project);
            $this->dm->flush();

            return $this->redirectToRoute('project_view', ['id' => $project->getId()]);
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/view/{id}", name="task_view")
     * @param Task $task
     * @Template()
     */
    public function viewAction(Task $task)
    {
        return [
            'task' => $task
        ];
    }

    /**
     * @Route("/take/{id}", name="task_take")
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function takeTask(Task $task)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute("main_page");
        } else {
            /**
             * @var User $user
             */
            $user = $this->getUser();
        }
        if($task->getProject()->getCreatedBy() === $user || in_array($user, $task->getProject()->getInvitedUsers()->toArray() )) {
            $task->setExecutor($user);
            $task->setStatus($this->dm->getRepository(TaskStatus::class)->findOneBy(['title' => TaskStatus::IN_PROGRESS]));
            $this->dm->persist($task);

            $this->dm->flush();
        }

        return $this->redirectToRoute('project_view', ['id' => $task->getProject()->getId()]);
    }

    /**
     * @Route("/complete/{id}", name="task_complete")
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completeTask(Task $task)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute("main_page");
        } else {
            /**
             * @var User $user
             */
            $user = $this->getUser();
        }

        if($task->getExecutor() === $user ) {
            $task->setStatus($this->dm->getRepository(TaskStatus::class)->findOneBy(['title' => TaskStatus::DONE]));
            $this->dm->persist($task);

            $this->dm->flush();
        }

        return $this->redirectToRoute('project_view', ['id' => $task->getProject()->getId()]);
    }
}
