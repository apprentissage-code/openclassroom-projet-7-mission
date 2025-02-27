<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('lastname', TextType::class, ['label' => 'Nom'])
      ->add('firstname', TextType::class, ['label' => 'Prénom'])
      ->add('mail', EmailType::class, ['label' => 'Email'])
      ->add('EntryDate', DateTimeType::class, [
        'widget' => 'single_text',
        'label' => 'Date d\'entrée'
      ])
      ->add('status', TextType::class, ['label' => 'Statut'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Employee::class,
    ]);
  }
}
