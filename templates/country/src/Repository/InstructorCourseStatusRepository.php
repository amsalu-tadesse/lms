<?php

namespace App\Repository;

use App\Entity\InstructorCourseStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstructorCourseStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstructorCourseStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstructorCourseStatus[]    findAll()
 * @method InstructorCourseStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstructorCourseStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstructorCourseStatus::class);
    }
    public function findInstructorCourseStatus($search=null)
    {
        $qb=$this->createQueryBuilder('p');
        if($search)
            $qb->andWhere("p.name  LIKE '%".$search."%'");

            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
     
            
        ;
    }
    // /**
    //  * @return InstructorCourseStatus[] Returns an array of InstructorCourseStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InstructorCourseStatus
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
