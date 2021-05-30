<?php


namespace App\Controller\Admin;


use App\Entity\Group;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatisticsController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/statistics", name="admin_statistics")
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function index(EntityManagerInterface $em): Response
    {
        $listGroups = $em->getRepository(Visit::class)->findGroupAndVisit();

        $forRender = parent::renderDefault();
        $forRender['listGroups'] = $listGroups;
        $forRender['title'] = 'Статистика посещаемости';

        return $this->render('admin/statistics/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/statistics/{id}/", name="admin_group_statistics")
     * @param Group $group
     * @param Request $request
     */

    public function StudentsFromGroup(Group $group, Request $request,EntityManagerInterface $em): Response
    {
        $listStudents = $em->getRepository(Student::class)->findStudentsFromGroup($group);

        $forRender = parent::renderDefault();
        $forRender['group'] = $group;
        $forRender['listStudents'] = $listStudents;
        $forRender['title'] = 'Статистика посещаемости';
        return $this->render('admin/statistics/students.html.twig', $forRender);
    }

    /**
     * @Route("/admin/statistics/{id}/student/{studentId}", name="admin_student_statistics")
     * @param $studentId
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function statistic($studentId, EntityManagerInterface $em): Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        $user = $student->getStudentId();
        $statistic = $this->getDoctrine()->getRepository(Visit::class)->findStatistic($studentId);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Статистика студента';
        $forRender['user'] = $user;
        $forRender['statistics'] = $statistic;
        $forRender['studentId'] = $studentId;
        return $this->render('admin/statistics/statistic.html.twig', $forRender);
    }
}