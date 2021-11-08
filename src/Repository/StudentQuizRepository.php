<?php

namespace App\Repository;

use App\Entity\StudentQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentQuiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentQuiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentQuiz[]    findAll()
 * @method StudentQuiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentQuiz::class);
    }

    // /**
    //  * @return StudentQuiz[] Returns an array of StudentQuiz objects
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
    public function findOneBySomeField($value): ?StudentQuiz
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getQuizesForStudent($course, $student)
    {
        return $this->createQueryBuilder('sq')
            ->select('q.passvalue', 'q.isMandatory', 'ch.id as chapter_id', 'sq.result')
            ->join('sq.quiz', 'q')
            ->join('q.instructorCourseChapter', 'ch')
            ->join('ch.instructorCourse','ic')
            ->where('ic.id = :val1')
            ->andWhere('sq.student = :val')
            ->setParameter('val1', $course)
            ->setParameter('val', $student)
            ->getQuery()
            ->getArrayResult()
        ;
    }


    // certification
}
