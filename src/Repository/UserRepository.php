<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry) 
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function filterUser($fname, $mname, $lname, $userType, $dep)
    {
        return  $this->createQueryBuilder('u')
            // ->join('u.userType','ut')
            ->innerJoin('u.department','dt')
            ->Where('u.firstName LIKE :firstName')
            ->andWhere('u.middleName LIKE :middleName')
            ->andWhere('u.lastName LIKE :lastName')
            ->andWhere('u.username LIKE :username')
            ->andWhere('dt.id LIKE :department')
            ->setParameter('firstName', '%'.$fname.'%')
            ->setParameter('middleName', '%'.$mname.'%')
            ->setParameter('lastName', '%'.$lname.'%')
            ->setParameter('username', '%'.$userType.'%')
            ->setParameter('department', '%'.$dep.'%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    
    // public function findUser($value)
    // {
    //     return $this->createQueryBuilder('u')
    //         ->where('u.email = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('u.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?User
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
