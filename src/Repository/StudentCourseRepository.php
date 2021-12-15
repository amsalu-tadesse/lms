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
<<<<<<< HEAD
            ->select('s.id as sid', 'ic.id','c.name','c.description', 's.status', 'u.firstName', 'u.middleName', 'u.lastName', 'st.student_id')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.instructor', 'i')
            ->join('s.student', 'st')
            ->join('i.user','u')
=======
            ->select('ic.id', 'c.name', 'c.description', 's.status', 'u.firstName', 'u.middleName', 'u.lastName')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.instructor', 'i')
            ->join('i.user', 'u')
>>>>>>> f852e5d663153c1586c729af0977bf3786edc225
            ->join('ic.course', 'c')
            ->Where('s.student = :val')
            ->andWhere('s.active = 1')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findRequests($student, $course)
    {
        $q = $this->createQueryBuilder('sc')
            ->innerJoin('sc.instructorCourse', 'ic')
            ->innerJoin('ic.course', 'c')
            ->join('sc.student', 's')
            ->join('s.user', 'u')
            ->where('sc.status = 0')
            ->andWhere('u.email LIKE :student')
            ->setParameter('student', '%'.$student.'%');
        if ($course != "") {
            $q->andWhere('ic.id = :course')
                ->setParameter('course', $course);
        }
        $q->orderBy('sc.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $q;
    }

    public function findRequestsApproved($student, $course)
    {
        $q = $this->createQueryBuilder('sc')
            ->innerJoin('sc.instructorCourse', 'ic')
            ->innerJoin('ic.course', 'c')
            ->join('sc.student', 's')
            ->join('s.user', 'u')
            ->where('sc.status = 1')
            ->andWhere('u.email LIKE :student')
            ->setParameter('student', '%'.$student.'%');
        if ($course != "") {
            $q->andWhere('ic.id = :course')
                ->setParameter('course', $course);
        }
        $q->orderBy('sc.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $q;
    }

    public function findRequestsRejected($student, $course)
    {
        $q = $this->createQueryBuilder('sc')
            ->innerJoin('sc.instructorCourse', 'ic')
            ->innerJoin('ic.course', 'c')
            ->join('sc.student', 's')
            ->join('s.user', 'u')
            ->where('sc.status = 2')
            ->andWhere('u.email LIKE :student')
            ->setParameter('student', '%'.$student.'%');
        if ($course != "") {
            $q->andWhere('ic.id = :course')
                ->setParameter('course', $course);
        }
        $q->orderBy('sc.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $q;
    }

    /**
     * @return StudentCourse[] Returns an array of Product objects
     */
    public function findRequest($user_id, $value)
    {
        return $this->createQueryBuilder('s')
            ->select('c.name', 'c.description', 's.id', 'c.id as course_id', 'ic.id as instructor_course_id', 's.createdAt', 's.status')
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
            ->select('c.name', 'c.description', 's.id', 'c.id as course_id', 's.createdAt', 's.status')
            ->join('s.instructorCourse', 'ic')
            ->join('ic.course', 'c')
            ->Where('s.student = :val')
            ->andWhere('s.status = 0')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
<<<<<<< HEAD
    } 
    
    public function getStudentCertificate($student, $id)
    {
        return $this->createQueryBuilder('s')
            // ->select('s', 'st', 'u')
            ->join('s.student', 'st')
            ->join('st.user', 'u')
            ->Where('st.student_id = :val')
            ->andWhere('s.id = :val2')
            ->setParameter('val2', $id)
            ->setParameter('val', $student)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } 
=======
    }
>>>>>>> f852e5d663153c1586c729af0977bf3786edc225

    public function count($t)
    {
        return $this
            ->createQueryBuilder('st')
            ->select("count(st.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getRequiredDTData($start, $length, $orders, $search, $columns, $instcrs)
    {
        $searchItem = $search['value'];
        $columnNum = $orders[0]['column'];
        $orderColumn = $columns[$columnNum]['data'];
        $orderDir = $orders[0]['dir'];

        // Create Main Query
        $query = $this->createQueryBuilder('sc');

        // Create Count Query
        $countQuery = $this->createQueryBuilder('stCount');
        $countQuery->select('COUNT(stCount.id)');

        // Create inner joins
        $query

            ->select("sc.id, concat(u.firstName,' ',u.middleName,' ',u.lastName) as name", 'sc.isAtPage as page', 'sc.active', 'sc.createdAt', 'st.id as student')
            ->innerJoin('sc.student', "st")
            ->innerJoin('sc.instructorCourse', "ic")
            ->innerJoin('st.user', 'u');
        $countQuery
            ->innerJoin('stCount.student', "st")
            ->innerJoin('stCount.instructorCourse', "ins")
            ->innerJoin('st.user', 'u');

        //if all columns are from the same table you can use this

        // $count = sizeof($columns);
        // $flag = 0;

        $searchQuery =  "concat(u.firstName,' ',u.middleName,' ',u.lastName) LIKE '%".$searchItem.'%\' ';
        $query->Where($searchQuery);
        $countQuery->Where($searchQuery);
        $countQuery->setMaxResults(10);
        //order
        $query->andWhere("ic.id= :inst1");
        $query->setParameter('inst1', $instcrs);
        $countQuery->andWhere("ins.id=:inst2");
        $countQuery->setParameter('inst2', $instcrs);

        if ($columns[$columnNum]['data'] == "name") {
            $query->orderBy("name", $orderDir);
        } else {
            $query->orderBy("sc.$orderColumn", $orderDir);
        }

        // Limit
        $query->setFirstResult($start)->setMaxResults($length);
        // Execute
        $results = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();


        return array(
            "results"       => $results,
            "countResult"   => $countResult
        );
    }

    /**
     * @return Product[] Returns an array of Product objects
     */

    public function filterData($name)
    {
        return  $this->createQueryBuilder('st')
            ->innerJoin('st.student', "st")
            ->innerJoin('st.u', 'u')
            ->Where("u.firstName LIKE :name")

            ->setParameter('name', '%'.$name.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function newCourseRequest()
    {
        return $this->createQueryBuilder('sc')
            ->select('count(sc.id) as new_requests')
            ->where('sc.directorNotification = 0')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateNotification()
    {
        return $this->createQueryBuilder('sc')
            ->update()
            ->set('sc.directorNotification ', '1')
            ->getQuery()
            ->execute();
    }

    public function getStudents($course, $status, $inst)
    {
        $query = $this->createQueryBuilder('sc')
                // ->select("concat(u.firstName,' ',u.middleName,' ',u.lastName) as name", "u.email",'c.name' )
                ->join('sc.student', 'st')
                ->join('st.user', 'u')
                ->join('sc.instructorCourse', 'ic')
                ->join('ic.course', 'c');
            if($course){
                $query->where('ic.course = :val')
                      ->setParameter("val", $course);
            }

            if($status != 0)
            {
                $query->andWhere('sc.status = :val1')
                      ->setParameter("val1", $status);
            }

            if($inst){
                $query->andWhere('ic.instructor = :val2')
                      ->setParameter("val2", $inst);
            }
        
        $students = $query->getQuery()->getResult();
        return $students;
    }
}
