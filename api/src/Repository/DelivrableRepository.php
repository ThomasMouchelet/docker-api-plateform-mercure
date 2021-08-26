<?php

namespace App\Repository;

use App\Entity\Delivrable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Delivrable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delivrable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delivrable[]    findAll()
 * @method Delivrable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelivrableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delivrable::class);
    }

    // /**
    //  * @return Delivrable[] Returns an array of Delivrable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Delivrable
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
