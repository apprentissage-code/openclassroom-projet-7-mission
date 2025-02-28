<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

final class TaskController extends AbstractController
{
  #[Route('project/{project_id}/task/add', name: 'app_new_task', methods: ['GET', 'POST'], requirements: ['project_id' => '\d+'])]
  #[ParamConverter('project', options: ['mapping' => ['project_id' => 'id']])]
  public function new(Project $project, Request $request, EntityManagerInterface $entityManager, EmployeeRepository $employeeRepository): Response
  {
    $task = new Task();
    $task->setProject($project);

    $employees = $employeeRepository->findByProject($project);
    $form = $this->createForm(TaskFormType::class, $task, ['employees' => $employees]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($task);
      $entityManager->flush();

      return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
    }

    return $this->render('task/new.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('project/{project_id}/task/{task_id}/edit', name: 'app_task_edit', requirements: ['project_id' => '\d+', 'task_id' => '\d+'])]
  #[ParamConverter('project', options: ['mapping' => ['project_id' => 'id']])]
  #[ParamConverter('task', options: ['mapping' => ['task_id' => 'id']])]
  public function edit(Project $project, Task $task, Request $request, EntityManagerInterface $entityManager, EmployeeRepository $employeeRepository): Response
  {
    $employees = $employeeRepository->findByProject($project);
    $form = $this->createForm(TaskFormType::class, $task, ['employees' => $employees]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_project', ['id' => $task->getProject()->getId()]);
    }

    return $this->render('task/edit.html.twig', [
      'form' => $form,
      'task' => $task,
    ]);
  }

  #[Route('project/{project_id}/task/{task_id}/remove', name: 'app_task_remove', requirements: ['project_id' => '\d+', 'id' => '\d+'])]
  #[ParamConverter('project', options: ['mapping' => ['project_id' => 'id']])]
  #[ParamConverter('task', options: ['mapping' => ['task_id' => 'id']])]
  public function remove(Task $task, EntityManagerInterface $entityManager): Response
  {
    $entityManager->remove($task);
    $entityManager->flush();
    return $this->redirectToRoute('app_project', ['id' => $task->getProject()->getId()]);
  }
}
