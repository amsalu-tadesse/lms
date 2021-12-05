<?php

namespace App\Repository;

use App\Entity\InstructorCourseChapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstructorCourseChapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstructorCourseChapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstructorCourseChapter[]    findAll()
 * @method InstructorCourseChapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstructorCourseChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstructorCourseChapter::class);
    }

    public function findChaptersInCourse($value)
    {
        return $this->createQueryBuilder('ch')
            ->select('ch')
            ->join('ch.instructorCourse', 'ic')
            ->andWhere('ic.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findChapters($id, $student)
    {
        return $this->createQueryBuilder('icc')
            ->select('icc','sc.pagesCompleted','ic')
            ->leftjoin('icc.studentChapters', 'sc')
            ->join('icc.instructorCourse', 'ic')
            ->join('ic.studentCourses','scourse')
            ->where('ic.id = :id')
            ->andWhere('scourse.student = :student')
            ->setParameter('id', $id)
            ->setParameter('student', $student)
            ->orderBy('icc.id','asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findChapter1($course, $chapter)
    {
        return $this->createQueryBuilder('ch')
            ->select('ch')
            ->join('ch.instructorCourse', 'ic')
            ->where('ch.topic = :id')
            ->andWhere('ic.id = :val')
            ->setParameter('id', $chapter)
            ->setParameter('val', $course)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getChaptersWithContentForCourse($value)
    {
        return $this->createQueryBuilder('ch')
        ->select('c','ch')
        ->leftJoin('ch.contents', 'c')
        ->join('ch.instructorCourse','ic')
        ->where('ic.id = :val')
        ->orderBy('ch.id')
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
    ;
    }
    // /**
    //  * @return InstructorCourseChapter[] Returns an array of InstructorCourseChapter objects
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
    public function findOneBySomeField($value): ?InstructorCourseChapter
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
