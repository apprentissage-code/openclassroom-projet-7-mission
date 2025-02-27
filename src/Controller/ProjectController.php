<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
  #[Route('/', name: 'app_home')]
  public function index(ProjectRepository $projectRepository): Response
  {
    $projects = $projectRepository->findActiveProjects();

    return $this->render('project/index.html.twig', [
      'projects' => $projects,
    ]);
  }

  #[Route('/project/add', name: 'app_new_project', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $project = new Project();
    $form = $this->createForm(ProjectFormType::class, $project);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($project);
      $entityManager->flush();

      return $this->redirectToRoute('app_home');
    }

    return $this->render('project/new.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/project/{id}/archive', name: 'app_project_archive', requirements: ['id' => '\d+'])]
  public function archive(Project $project, EntityManagerInterface $entityManager): Response
  {
    if (!$project) {
      return $this->redirectToRoute('app_home');
    }

    $project->setDateArchivage(new DateTime());
    $entityManager->flush();
    return $this->redirectToRoute('app_home');
  }
}
