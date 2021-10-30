<?php

namespace App\Repository;

use App\Entity\Termsandconditions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Termsandconditions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Termsandconditions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Termsandconditions[]    findAll()
 * @method Termsandconditions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermsandconditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Termsandconditions::class);
    }

    // /**
    //  * @return Termsandconditions[] Returns an array of Termsandconditions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Termsandconditions
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
