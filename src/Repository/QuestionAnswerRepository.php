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
            ->join('q.course', 'ic')
            ->andWhere('ic.instructor = :val')
            ->setParameter('val', $value)
            ->orderBy('q.createdAt', 'Desc')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getQuestionAnswer($course, $start, $end)
    {
        return $this->createQueryBuilder('q')
            ->select('q', 'u.username as instructor', 'stu.username as student')
            ->join('q.course', 'ic')
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

    public function newQuestionNotification($id)
    {
        return $this->createQueryBuilder('q')
            ->select('count(q.id) as new_questions')
            ->where('q.instructor = :val')
            ->andWhere('q.notification = 0')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateNotification($id)
    {
        return $this->createQueryBuilder('q')
            ->update()
            ->set('q.notification ', '1')
            ->where('q.instructor = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->execute();
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
