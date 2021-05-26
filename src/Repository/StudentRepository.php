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

    public function findStudentsFromGroupForTeacher($disciplineId, $groupId)
    {
        $db = $this->createQueryBuilder('s')
            ->select( 'su.first_name', 'su.middle_name', 'su.last_name', 's.id as studentId')
            ->where('s.group = :idG')
            ->setParameter('idG', $groupId)
            ->setParameter('idD', $disciplineId)
            ->leftJoin('s.visits', 'sv')
            ->leftJoin('s.group', 'sg')
            ->leftJoin('s.student', 'su')
            ->leftJoin('sv.discipline', 'svd')
            ->addSelect('SUM(CASE WHEN sv.discipline = :idD then 1 else 0 end) as count',' SUM(CASE WHEN sv.discipline = :idD and sv.plus = 2 then 1 else 0 end) as countMiss')
            ->orderBy('su.last_name', 'ASC')
            ->groupBy('s.id')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }
}
