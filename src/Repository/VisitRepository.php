<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function deleteDisciplineForTeacher($id, $disciplineId)
    {
        $db = $this->createQueryBuilder('v')
            ->delete()
            ->where('v.teacher = :idT','v.discipline = :idD')
            ->setParameter('idT', $id)
            ->setParameter('idD', $disciplineId)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findDisciplinesForTeacher($id)
    {
        $db = $this->createQueryBuilder('v')
            ->select( 'vd.id as disciplineId','vd.name_discipline', 'vt.id')
            ->where('vt.id = :idT')
            ->setParameter('idT', $id)
            ->leftJoin('v.discipline', 'vd')
            ->leftJoin('v.teacher', 'vt')
            ->groupBy('v.discipline')
            ->orderBy('vd.name_discipline')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findGroupsForTeacher($teacherId, $disciplineId)
    {
        $db = $this->createQueryBuilder('v')
            ->select( 'vd.id as disciplineId', 'vt.id as teacherId', 'vsg.group_name', 'vsg.id as groupId')
            ->where('vt.id = :idT','vd.id = :idD')
            ->setParameter('idT', $teacherId)
            ->setParameter('idD', $disciplineId)
            ->leftJoin('v.discipline', 'vd')
            ->leftJoin('v.teacher', 'vt')
            ->leftJoin('v.student', 'vs')
            ->leftJoin('vs.group', 'vsg')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findStudentsFromGroupForTeacher($disciplineId, $groupId)
    {
        $db = $this->createQueryBuilder('v')
            ->select('vsg.group_name', 'vd.name_discipline', 'vsu.first_name', 'vsu.middle_name', 'vsu.last_name', 'vs.id as studentId')
            ->where('vs.group = :idG')
            ->setParameter('idG', $groupId)
            ->setParameter('idD', $disciplineId)
            ->leftJoin('v.student', 'vs')
            ->leftJoin('vs.group', 'vsg')
            ->leftJoin('vs.student', 'vsu')
            ->leftJoin('v.discipline', 'vd')
            ->addSelect('SUM(CASE WHEN v.discipline = :idD then 1 else 0 end) as count',' SUM(CASE WHEN v.discipline = :idD and v.plus = 2 then 1 else 0 end) as countMiss')
            ->orderBy('vsu.last_name', 'ASC')
            ->groupBy('vs.id')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }
}
