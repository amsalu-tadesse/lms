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
