<?php

namespace App\Repository;

use App\Entity\StudentChapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentChapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentChapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentChapter[]    findAll()
 * @method StudentChapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentChapter::class);
    }

    // /**
    //  * @return StudentChapter[] Returns an array of StudentChapter objects
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

    public function getProgress1($value, $stud_id)
    {
        return $this->createQueryBuilder('s')
            ->where('s.student = :stud_id')
            ->andWhere('s.chapter = :val')
            ->setParameter('stud_id', $stud_id)
            ->setParameter('val', $value)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getProgress($course, $value, $stud_id)
    {
        return $this->createQueryBuilder('s')
            ->select('s.pagesCompleted', 's.id', 's.updated_at')
            ->join('s.chapter', 'ch')
            ->where('ch.instructorCourse = :course')
            ->andWhere('s.student = :stud_id')
            ->andWhere('ch.topic = :val')
            ->setParameter('course', $course)
            ->setParameter('stud_id', $stud_id)
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
