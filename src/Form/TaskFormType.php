<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Repository\EmployeeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $employees = $options['employees'];

    $builder
      ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
      ->add('description', TextType::class, ['label' => 'Description'])
      ->add('date', null, [
        'widget' => 'single_text',
        'label' => 'Date'
      ])
      ->add('status', EnumType::class, [
        'label' => 'Statut',
        'class' => TaskStatus::class,
        'choice_label' => fn(TaskStatus $status) => $status->getLabel(),
      ])
      ->add('member', EntityType::class, [
        'class' => Employee::class,
        'choice_label' => 'lastname',
        'label' => 'Membre',
        'placeholder' => 'Aucun membre assigné',
        'choices' => $employees,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Task::class,
      'employees' => null,
    ]);
  }
}
