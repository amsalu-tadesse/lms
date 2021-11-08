<?php

namespace App\Repository;

use App\Entity\SystemSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SystemSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemSetting[]    findAll()
 * @method SystemSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemSetting::class);
    }


    public function findSystemSetting($search=null)
    {
        $qb=$this->createQueryBuilder('p');
        if($search)
            $qb->andWhere("p.name  LIKE '%".$search."%'");

            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
     
            
        ;
    }

    public function getValue($code)
    {
        return $this->createQueryBuilder('s')
                ->select('s.value')
                ->where('s.code = :code')
                ->setParameter("code", $code)
                ->getQuery()
                ->getOneOrNullResult();
    }

    // /**
    //  * @return SystemSetting[] Returns an array of SystemSetting objects
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
    public function findOneBySomeField($value): ?SystemSetting
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
