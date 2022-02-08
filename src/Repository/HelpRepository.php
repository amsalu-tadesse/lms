<?php

namespace App\Repository;

use App\Entity\Help;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Help|null find($id, $lockMode = null, $lockVersion = null)
 * @method Help|null findOneBy(array $criteria, array $orderBy = null)
 * @method Help[]    findAll()
 * @method Help[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HelpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Help::class);
    }

    public function findHelp($search=null)
    {
        $qb=$this->createQueryBuilder('p');
        if ($search) {
            $qb->andWhere("p.label  LIKE '%".$search."%'");
        }

        return
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()


        ;
    }
    public function findAllAdminHelps()
    {
        $qb=$this->createQueryBuilder('p');
            $qb->andWhere('p.usertype != :std')
                ->setParameter('std', 4);
        return $qb->orderBy('p.id', 'ASC')
            ->getQuery()->getResult()
        ;
    }
    public function findAllStudHelps()
    {
        $qb=$this->createQueryBuilder('p');
            $qb->andWhere('p.usertype = :std')
                ->setParameter('std', 4);
        return $qb->orderBy('p.id', 'ASC')
            ->getQuery()->getResult()
        ;
    }

    // /**
    //  * @return Help[] Returns an array of Help objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Help
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
