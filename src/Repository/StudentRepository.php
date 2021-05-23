<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function deleteStudent($student)
    {
        $db = $this->createQueryBuilder('s')
            ->delete()
            ->where('s.id = :idS')
            ->setParameter('idS', $student)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function setGroup($group , $student)
    {
        $db = $this->createQueryBuilder('s')
            ->update()
            ->set('s.group', ':idG')
            ->setParameter('idG', $group)
            ->where('s.id = :idS')
            ->setParameter('idS', $student)
            ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findStudent()
    {
        $db = $this->createQueryBuilder('s')
            ->select( 's.id','su.id as SId','su.first_name', 'su.last_name', 'su.middle_name', 'sg.group_name')
            ->leftJoin('s.student', 'su')
            ->leftJoin('s.group','sg')
            ->orderBy('su.id', 'DESC')
        ;

        $query = $db->getQuery();
        return $query->execute();
    }

    // /**
    //  * @return Student[] Returns an array of Student objects
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
    public function findOneBySomeField($value): ?Student
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
