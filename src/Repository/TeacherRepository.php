<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function findDisciplines($id)
    {
        $db = $this->createQueryBuilder('t')
            ->select('t.id as teacherId','tvd.id as disciplineId','tvd.name_discipline','tu.first_name', 'tu.last_name', 'tu.middle_name')
            ->where('t.id = :idT')
            ->setParameter('idT', $id)
            ->join('t.teacher','tu')
            ->leftJoin('t.visits', 'tv')
            ->leftJoin('tv.discipline','tvd')
            ->orderBy('tvd.name_discipline', 'ASC')
            ->groupBy("tvd.id")
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function deleteTeacher($id)
    {
        $db = $this->createQueryBuilder('t')
            ->delete()
            ->where('t.id = :idT')
            ->setParameter('idT', $id)
        ;
        $query = $db->getQuery();
        return $query->execute();
    }



}
