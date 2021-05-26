<?php


namespace App\Controller\Main;


use App\Entity\Discipline;
use App\Entity\Student;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StudentController extends BaseController
{
    /**
     * @Route ("/user/student/", name="list_disciplines_student")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function listDisciplines(EntityManagerInterface $em):Response
    {
        $user = $this->getUser()->getId();
        $student = $em->getRepository('App:Student')->findOneBy(['student' => $user]);
        $id = $student->getId();
        $disciplines =  $em->getRepository('App:Visit')->findDisciplinesForStudent($id);

        $forRender = parent::renderDefault();
        $forRender['disciplines'] = $disciplines;
        return $this->render('main/authorized/student/disciplines.html.twig', $forRender);
    }

    /**
     * @Route ("/user/student/discipline/{disciplineId}/", name="student_statistic")
     * @param $disciplineId
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function statisticStudent($disciplineId, EntityManagerInterface $em):Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $student = $em->getRepository('App:Student')->findOneBy(['student' => $userId]);
        $studentId = $student->getId();
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->find($disciplineId);
        $statistic = $this->getDoctrine()->getRepository(Visit::class)->findStatisticForTeacher($disciplineId, $studentId);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Статистика студента по дисциплине - ';
        $forRender['user'] = $user;
        $forRender['discipline'] = $discipline;
        $forRender['statistics'] = $statistic;
        $forRender['disciplineId'] = $disciplineId;
        $forRender['studentId'] = $studentId;
        return $this->render('main/authorized/student/statistic.html.twig', $forRender);
    }
}