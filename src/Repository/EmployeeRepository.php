<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Employee::class);
  }

  public function findByProject(Project $project): array
  {
    return $this->createQueryBuilder('e')
      ->innerJoin('e.projects', 'p')
      ->where('p = :project')
      ->setParameter('project', $project)
      ->getQuery()
      ->getResult();
  }

  //    /**
  //     * @return Employee[] Returns an array of Employee objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('e')
  //            ->andWhere('e.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('e.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Employee
  //    {
  //        return $this->createQueryBuilder('e')
  //            ->andWhere('e.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
