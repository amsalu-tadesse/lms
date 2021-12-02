<?php

namespace App\Repository;

use App\Entity\StudentQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentQuestion[]    findAll()
 * @method StudentQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentQuestion::class);
    }

    public function find1($quiz, $student)
    {
        return $this->createQueryBuilder('sa')
            ->join('sa.student', 'st')
            ->leftJoin('sa.question', 'qq')
            ->join('qq.quizChoices', 'qc')
            ->join('qq.quiz', 'q')
            ->where('q.id = :quiz')
            ->andWhere('st.id = :val')
            ->andWhere('sa.active = 1')
            ->setParameter('quiz', $quiz)
            ->setParameter('val', $student)
            ->getQuery()
            ->getResult()
        ;
    }

    public function deactivateQuestions($quiz, $student)
    {
        return $this->createQueryBuilder('sq')
                ->update()
                ->where('sq.question.quiz = :quiz')
                ->andWhere('sq.student = :student')
                ->setParameter("quiz", $quiz)
                ->setParameter("student", $student)
                ->getQuery()
                ->execute();
    }

    // /**
    //  * @return StudentQuestion[] Returns an array of StudentQuestion objects
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
    public function findOneBySomeField($value): ?StudentQuestion
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
