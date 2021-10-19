<?php

namespace App\Repository;

use App\Entity\InstructorCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstructorCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstructorCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstructorCourse[]    findAll()
 * @method InstructorCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstructorCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstructorCourse::class);
    }

    // /**
    //  * @return InstructorCourse[] Returns an array of InstructorCourse objects
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

    public function findCoursesSortByCategory()
    {
        return $this->createQueryBuilder('ic')
            ->select('u.firstName','u.middleName', 'u.lastName', 'c.name','c.description', 'c.code', 'c.id', 'cc.id as category_id', 'cc.name as category_name' )
            ->join('ic.instructor', 'i')
            ->join('i.user','u')
            ->join('ic.course', 'c')
            ->join('c.category','cc')
            ->Where('c.status = 1')
            ->orderBy('c.category')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByUser($user)
    {
        return $this->createQueryBuilder('i')
            ->join('i.instructor', 'inst')
            // ->join('inst.user', 'user')
            ->andWhere('inst.user = :usr')
            ->setParameter('usr', $user)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
