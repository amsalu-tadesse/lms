<?php

namespace App\Repository;

use App\Entity\StudentContentReaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentContentReaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentContentReaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentContentReaction[]    findAll()
 * @method StudentContentReaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentContentReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentContentReaction::class);
    }

    // /**
    //  * @return StudentContentReaction[] Returns an array of StudentContentReaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentContentReaction
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
