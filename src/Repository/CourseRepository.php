<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findCourse($search=null)
    {
        $qb=$this->createQueryBuilder('c');
        if($search)
            // $qb->select('c','cc.name as categoy')
            $qb->andWhere("c.name  LIKE '%".$search."%'");
            $qb->join('c.category', 'cc');
            return 
            $qb->orderBy('c.id', 'ASC')
            ->getQuery();
    }

    public function courseWithStudentNumber($status = 0)
    {
        $courses = $this->createQueryBuilder('c');
        
        if($status){
            //students who finished same course with different instructors counted as many of the courses
            if($status == 5){
                $courses->select('c.id', 'count(sc.student) as finished');
                $courses->where('sc.status = 5');
            }
            else{
                $courses->select('c.id', 'count(sc.student) as non_finished');
                $courses->where('sc.status = 1');
            }
        }else{
            $courses->select('c.id', 'count(sc.student) as total_student');
        }

        $courses->join('c.instructorCourses', 'ic')
        ->join('ic.studentCourses', 'sc')
        ->groupBy('c.id')
        ->getQuery();
        
        return $courses->getDQL();
    }

    public function courseWithInstructorNumber()
    {
        return $this->createQueryBuilder('c')
                ->select('c.id', 'count(distinct t.id) as totla_instructor')
                ->join('c.instructorCourses', 'ic')
                ->join('ic.instructor','t')
                ->groupBy('c.id')
                ->getQuery()
                ->getResult();          
    }

    public function findTotalActiveStudent($id)
    {
        return $this->createQueryBuilder('c')
                ->select('count(sc.student) as total_student')
                ->join('c.instructorCourses', 'ic')
                ->join('ic.studentCourses', 'sc')
                ->where('sc.status = 1')
                ->andWhere('c.id = :id')
                ->setParameter("id", $id)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function getChaptersCount()
    {
        return $this->createQueryBuilder('c')
        ->select('c.id','count(ch.id) as total')
        ->join('c.instructorCourses', 'ic')
        ->join('ic.instructorCourseChapters','ch')
        ->where('ic.active = 1')
        ->groupBy('c.id')
        ->getQuery()
        ->getResult()
        ;
    }
    // /**
    //  * @return Course[] Returns an array of Course objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
