<?php

namespace App\Repository;

use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method UserGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroup[]    findAll()
 * @method UserGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }

    public function findUserGroup($search=null)
    {
        $qb=$this->createQueryBuilder('u');
        if($search)
            $qb->andWhere("u.name  LIKE '%".$search."%'");

            return 
            $qb->orderBy('u.id', 'ASC')
            ->getQuery()
     
        ;
    }

    public function filterUserGroup($qua, $date, $prod, $comp, $store)
    {
        return  $this->createQueryBuilder('ug')
            ->Where('ug.name LIKE :name')
            ->andWhere('ug.description LIKE :description')
            ->andWhere('ug.isActive LIKE :isActive')
            ->setParameter('name', '%'.$qua.'%')
            ->setParameter('description', '%'.$store.'%')
            ->setParameter('isActive', '%'.$comp.'%')
            ->orderBy('ug.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return UserGroup[] Returns an array of UserGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserGroup
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
