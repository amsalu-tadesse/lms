<?php

namespace App\Repository;

use App\Entity\AcademicLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Choices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choices[]    findAll()
 * @method Choices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcademicLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicLevel::class);
    }

    public function findAcademicLevel($search=null)
    {
        $qb=$this->createQueryBuilder('p');
        if($search)
            $qb->andWhere("p.name  LIKE '%".$search."%'");

            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
     
            
        ;
    }
    // /**
    //  * @return Choices[] Returns an array of Choices objects
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

    /*
    public function findOneBySomeField($value): ?Choices
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
