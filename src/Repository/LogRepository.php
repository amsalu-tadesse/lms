<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function findLog($name, $createdAt, $action, $table)
    {
        $q =  $this->createQueryBuilder('l')
                ->join('l.actor', 'u');

        // ->innerJoin('u.department','dt')
        // if ($name !="") {
        //     $q->Where('u.firstName LIKE :firstName')
        //           ->setParameter('firstName', '%'.$name.'%');
        // }
        // if ($name !="") {
        //     $q->andWhere('u.middleName LIKE :middleName')
        //         ->setParameter('middleName', '%'.$name.'%');
        // }

        if ($name !="") {
            $q->andWhere('l.actor = :actor')
                  ->setParameter('actor', $name);
        }

        if($action != ""){
            $q->andWhere('l.action = :action')
            ->setParameter("action", $action);
        }

        if($table != ""){
            $q->andWhere("l.modifiedEntity = :entity")
            ->setParameter('entity', $table);
        }



        $q->orderBy('l.id', 'DESC')
            ->getQuery()
        ;
        return $q;
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
