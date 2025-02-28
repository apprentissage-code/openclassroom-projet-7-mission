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
  public function edit(Employee $employee, Request $request, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(EmployeeFormType::class, $employee);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_employees');
    }

    return $this->render('employee/edit.html.twig', [
      'form' => $form,
      'employee' => $employee
    ]);
  }

  #[Route('/employee/{id}/remove', name: 'app_employee_remove', requirements: ['id' => '\d+'])]
  public function remove(Employee $employee, EntityManagerInterface $entityManager): Response
  {

    $entityManager->remove($employee);
    $entityManager->flush();
    return $this->redirectToRoute('app_employees');
  }
}
