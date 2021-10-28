<?php

namespace App\Repository;

use App\Entity\StudentCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentCourse[]    findAll()
 * @method StudentCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentCourse::class);
    }

    // /**
    //  * @return StudentCourse[] Returns an array of StudentCourse objects
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
    public function findOneBySomeField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @return StudentCourse[] Returns an array of Product objects
     */
    public function findCourses($value)
    {
        return $this->createQueryBuilder('s')
            ->select('ic.id','c.name','c.description', 's.status', 'u.firstName', 'u.middleName', 'u.lastName')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.instructor', 'i')
            ->join('i.user','u')
            ->join('ic.course', 'c')
            ->Where('s.student = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findRequests($student, $course, $date)
    {
        return  $this->createQueryBuilder('sc')
            ->innerJoin('sc.instructorCourse','ic')
            ->innerJoin('ic.course','c')
            ->join('sc.student', 's')
            ->join('s.user', 'u')
            ->Where('c.name LIKE :course')
            ->andWhere('u.email LIKE :student')
            ->andWhere('sc.createdAt LIKE :date')
         
            ->setParameter('student', '%'.$student.'%')
            ->setParameter('course', '%'.$course.'%')
            ->setParameter('date', '%'.$date.'%')
            
            ->orderBy('sc.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return StudentCourse[] Returns an array of Product objects
     */
    public function findRequest($user_id, $value)
    {
        return $this->createQueryBuilder('s')
            ->select('c.name','c.description','s.id', 'c.id as course_id', 'ic.id as instructor_course_id', 's.createdAt', 's.status')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.course', 'c')
            ->where('s.id = :id')
            ->andWhere('s.student = :val')
            ->andWhere('s.status = 0')
            ->setParameter('id', $value)
            ->setParameter('val', $user_id)
            ->getQuery()
            ->getOneorNullResult()
        ;
    }

    public function findRequestedCourses($value)
    {
        return $this->createQueryBuilder('s')
            ->select('c.name','c.description','s.id', 'c.id as course_id', 's.createdAt', 's.status')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.course', 'c')
            ->Where('s.student = :val')
            ->andWhere('s.status = 0')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }    
}

