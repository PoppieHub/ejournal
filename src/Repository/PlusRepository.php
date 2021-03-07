<?php

namespace App\Repository;

use App\Entity\Plus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plus[]    findAll()
 * @method Plus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plus::class);
    }

    // /**
    //  * @return Plus[] Returns an array of Plus objects
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
    public function findOneBySomeField($value): ?Plus
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
