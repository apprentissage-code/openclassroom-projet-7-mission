<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeFormType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
  #[Route('/employees', name: 'app_employees')]
  public function index(EmployeeRepository $employeeRepository): Response
  {
    $employees = $employeeRepository->findAll();

    return $this->render('employee/employees.html.twig', [
      'employees' => $employees,
    ]);
  }

  #[Route('/employee/{id}/edit', name: 'app_employee_edit', requirements: ['id' => '\d+'])]
  public function edit(int $id, EmployeeRepository $employeeRepository, Request $request, EntityManagerInterface $entityManager): Response
  {
    $employee = $employeeRepository->find($id);

    if (!$employee) {
      throw $this->createNotFoundException('Employé non trouvé.');
    }

    $form = $this->createForm(EmployeeFormType::class, $employee);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_employees');
    }

    return $this->render('employee/edit.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/employee/{id}/remove', name: 'app_employee_remove', requirements: ['id' => '\d+'])]
  public function remove(Employee $employee, EntityManagerInterface $entityManager): Response
  {
    if (!$employee) {
      return $this->redirectToRoute('app_employees');
    }

    $entityManager->remove($employee);
    $entityManager->flush();
    return $this->redirectToRoute('app_employees');
  }
}
