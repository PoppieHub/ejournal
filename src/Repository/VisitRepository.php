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
            ->groupBy('vsg.id')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findStatisticForTeacher($disciplineId, $studentId)
    {
        $db = $this->createQueryBuilder('v')
            ->select('vp.operation', 'v.date as dateTime','v.id')
            ->where('v.discipline = :idD', 'v.student = :idS')
            ->setParameter('idD', $disciplineId)
            ->setParameter('idS', $studentId)
            ->leftJoin('v.plus', 'vp')
            ->orderBy('v.date', 'DESC')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findDisciplinesForStudent($id)
    {
        $db = $this->createQueryBuilder('v')
            ->select( 'vd.id as disciplineId','vd.name_discipline', 'vs.id')
            ->where('vs.id = :idS')
            ->setParameter('idS', $id)
            ->leftJoin('v.discipline', 'vd')
            ->leftJoin('v.student', 'vs')
            ->groupBy('v.discipline')
            ->orderBy('vd.name_discipline')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function deleteStudentVisit($id)
    {
        $db = $this->createQueryBuilder('v')
            ->delete()
            ->where('v.student = :idS')
            ->setParameter('idS', $id)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function deleteDisciplineVisit($id)
    {
        $db = $this->createQueryBuilder('v')
            ->delete()
            ->where('v.discipline = :idD')
            ->setParameter('idD', $id)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function deleteMarkVisit($id)
    {
        $db = $this->createQueryBuilder('v')
            ->delete()
            ->where('v.plus = :idP')
            ->setParameter('idP', $id)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findGroupAndVisit()
    {
        $db = $this->createQueryBuilder('v')
            ->select('vsg.id','vsg.group_name','SUM(CASE WHEN v.plus = 1 or v.plus = 2 then 1 else 0 end) as count','SUM(CASE WHEN v.plus = 2 then 1 else 0 end) as countMiss')
            ->leftJoin('v.student', 'vs')
            ->leftJoin('vs.group','vsg')
            ->groupBy('vsg.id')
            ->orderBy('vsg.group_name', 'ASC')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findStatistic($studentId)
    {
        $db = $this->createQueryBuilder('v')
            ->select('vp.operation', 'v.date as dateTime','v.id','vd.name_discipline')
            ->where( 'v.student = :idS')
            ->setParameter('idS', $studentId)
            ->leftJoin('v.plus', 'vp')
            ->leftJoin('v.discipline', 'vd')
            ->orderBy('v.date', 'DESC')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }
}
