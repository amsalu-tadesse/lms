<?php

namespace App\Repository;

use App\Entity\AcademicLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AcademicLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcademicLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcademicLevel[]    findAll()
 * @method AcademicLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcademicLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicLevel::class);
    }

    // /**
    //  * @return AcademicLevel[] Returns an array of AcademicLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AcademicLevel
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
