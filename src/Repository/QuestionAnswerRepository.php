<?php

namespace App\Repository;

use App\Entity\QuestionAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionAnswer[]    findAll()
 * @method QuestionAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionAnswer::class);
    }

    // /**
    //  * @return QuestionAnswer[] Returns an array of QuestionAnswer objects
    //  */
    
    public function getQuestions($value)
    {
        return $this->createQueryBuilder('q')
            ->join('q.course','ic')
            ->andWhere('ic.id = :val')
            ->setParameter('val', $value)
            ->orderBy('q.createdAt', 'Desc')
            ->setFirstResult(1)
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getQuestionAnswer($course, $start, $end)
    {
        return $this->createQueryBuilder('q')
            ->select('q','u.username as instructor', 'stu.username as student')
            ->join('q.course','ic')
            ->leftJoin('q.student', 'stud')
            ->leftJoin('stud.user', 'stu')
            ->leftJoin('q.instructor', 'ins')
            ->leftJoin('ins.user', 'u')
            ->andWhere('ic.id = :val')
            ->setParameter('val', $course)
            ->orderBy('q.createdAt', 'Desc')
            ->setFirstResult($start)
            ->setMaxResults($end)
            ->getQuery()
            ->getArrayResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?QuestionAnswer
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
