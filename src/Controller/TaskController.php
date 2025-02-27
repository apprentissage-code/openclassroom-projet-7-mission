<?php

namespace App\Controller;

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
  #[Route('project/{project_id}/task/{task_id}/edit', name: 'app_task_edit', requirements: ['{project_id}' => '\d+', '{task_id}' => '\d+'])]
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
      'task' => $task
    ]);
  }
}
