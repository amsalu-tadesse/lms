<?php

namespace App\Repository;

use App\Entity\QuizChoices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizChoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizChoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizChoices[]    findAll()
 * @method QuizChoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizChoicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizChoices::class);
    }

    // /**
    //  * @return QuizChoices[] Returns an array of QuizChoices objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizChoices
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
