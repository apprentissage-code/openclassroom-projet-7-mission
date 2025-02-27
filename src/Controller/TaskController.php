<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
  #[Route('project/{project_id}/task/add', name: 'app_new_task', methods: ['GET', 'POST'], requirements: ['project_id' => '\d+'])]
  public function new(int $project_id, Request $request, EntityManagerInterface $entityManager): Response
  {
    $task = new Task();

    $project = $entityManager->getRepository(Project::class)->find($project_id);
    $task->setProject($project);

    $form = $this->createForm(TaskFormType::class, $task);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($task);
      $entityManager->flush();

      return $this->redirectToRoute('app_project', ['id'=> $project_id]);
    }

    return $this->render('task/new.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('project/{project_id}/task/{task_id}/edit', name: 'app_task_edit', requirements: ['project_id' => '\d+', 'task_id' => '\d+'])]
  public function edit(int $task_id, int $project_id, TaskRepository $taskRepository, Request $request, EntityManagerInterface $entityManager): Response
  {
    $task = $taskRepository->find($task_id);

    if (!$task) {
      throw $this->createNotFoundException('Tâche non trouvée.');
    }

    $form = $this->createForm(TaskFormType::class, $task);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_project', ['id'=> $project_id]);
    }

    return $this->render('task/edit.html.twig', [
      'form' => $form,
      'task' => $task,
      'projectID' => $project_id
    ]);
  }

  #[Route('project/{project_id}/task/{id}/remove', name: 'app_task_remove', requirements: ['project_id' => '\d+', 'id' => '\d+'])]
  public function remove(int $project_id, Task $task, EntityManagerInterface $entityManager): Response
  {
    if (!$task) {
      return $this->redirectToRoute('app_project', ['id'=> $project_id]);
    }

    $entityManager->remove($task);
    $entityManager->flush();
    return $this->redirectToRoute('app_project', ['id'=> $project_id]);
  }
}
