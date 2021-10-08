<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }


    public function findContent($search=null, $instructorCourse)
    {
        $qb=$this->createQueryBuilder('c');
        if ($search) {
            $qb->andWhere("c.title  LIKE '%".$search."%'");
        }
        if ($instructorCourse) {
            $qb->join('c.chapter', 'chptr')
               ->andWhere("chptr.instructorCourse = :instcrs")
               ->setParameter('instcrs', $instructorCourse);
        }

        return
            $qb->orderBy('c.id', 'ASC')
            ->getQuery()
        ;
    }

    public function getContentsForChapter($course, $chapter)
    {
        return $this->createQueryBuilder('c')
            ->Join('c.chapter', 'ch')
            ->join('ch.instructorCourse', 'ic')
            ->andWhere('ch.chapter = :val')
            ->andWhere('ic.course = :val2')
            ->setParameter('val', $chapter)
            ->setParameter('val2', $course)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getContentsCount($value)
    {
        return $this->createQueryBuilder('c')
            ->select('ch.id', 'count(c.videoLink) + count(c.filename) as total_video', 'count(c.content) as con')
            ->Join('c.chapter', 'ch')
            ->join('ch.instructorCourse', 'ic')
            ->andWhere('ic.id = :val')
            ->setParameter('val', $value)
            ->groupBy('ch.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getHtmlContent($value)
    {
        return $this->createQueryBuilder('c')
            ->select('c.content')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    // /**
    //  * @return Content[] Returns an array of Content objects
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
    public function findOneBySomeField($value): ?Content
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
