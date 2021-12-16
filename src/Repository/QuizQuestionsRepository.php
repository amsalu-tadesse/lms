<?php

namespace App\Repository;

use App\Entity\QuizQuestions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizQuestions|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestions|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestions[]    findAll()
 * @method QuizQuestions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestions::class);
    }

    public function getQ($chpater)
    {
        return $this->createQueryBuilder('qq')
            ->select('qq', 'cc')
            ->leftJoin('qq.quizChoices', 'cc')
            ->where('qq.quiz = :id')
            ->setParameter('id', $chpater)
            ->orderBy(' cc.id,qq.id', "desc")
            ->getQuery()
            ->getArrayResult();
    }
    // /**
    //  * @return QuizQuestions[] Returns an array of QuizQuestions objects
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
    public function findOneBySomeField($value): ?QuizQuestions
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
