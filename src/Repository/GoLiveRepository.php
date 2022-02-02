<?php

namespace App\Repository;

use App\Entity\GoLive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GoLive|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoLive|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoLive[]    findAll()
 * @method GoLive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoLiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoLive::class);
    }

    // /**
    //  * @return GoLive[] Returns an array of GoLive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoLive
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
