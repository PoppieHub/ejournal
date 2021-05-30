<?php


namespace App\Controller\Main;

use App\Entity\Discipline;
use App\Entity\Plus;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class TeacherController extends HomeController
{
    /**
     * @Route ("/user/teacher/", name="list_disciplines")
     */
    public function listDisciplines(EntityManagerInterface $em, Session $session):Response
    {
        $teacherId = $session->get('user')->getTeacher()->getId();
        $session->set('teacherId',$teacherId);
        $disciplines =  $em->getRepository('App:Visit')->findDisciplinesForTeacher($teacherId);

        $forRender = parent::renderDefault();
        $forRender['disciplines'] = $disciplines;
        return $this->render('main/authorized/teacher/disciplines.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/discipline/{disciplineId}", name="list_groups")
     * @param $disciplineId
     * @return Response
     */

    public function listGroups($disciplineId, EntityManagerInterface $em,Session $session): Response
    {
        $teacherId = $session->get('teacherId');
        $groupsAll = $em->getRepository('App:Group')->findAll();
        $groups = $em->getRepository('App:Visit')->findGroupsForTeacher($teacherId,$disciplineId);

        $forRender = parent::renderDefault();
        $forRender['groups'] = $groups;
        $forRender['groupsAll'] = $groupsAll;
        $forRender['disciplineId'] = $disciplineId;

        return $this->render('main/authorized/teacher/groups.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/discipline/{disciplineId}/group/{groupId}", name="list_students")
     * @param $disciplineId
     * @param $groupId
     * @param EntityManagerInterface $em
     * @param Session $session
     * @return Response
     */

    public function listStudents($disciplineId, $groupId, EntityManagerInterface $em, Session $session):Response
    {
        $teacherId = $session->get('teacherId');
        $students = $em->getRepository('App:Student')->findStudentsFromGroupForTeacher($disciplineId, $groupId);
        $group = $em->getRepository('App:Group')->find($groupId);
        $discipline = $em->getRepository('App:Discipline')->find($disciplineId);

        $forRender = parent::renderDefault();
        $forRender['teacherId'] = $teacherId;
        $forRender['disciplineId'] = $disciplineId;
        $forRender['students'] = $students;
        $forRender['group'] = $group;
        $forRender['discipline'] = $discipline;
        $forRender['title'] = 'Группа - ';
        return $this->render('main/authorized/teacher/students.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/discipline/{disciplineId}/group/{groupId}/student/{studentId}/plus/{plus}/", name="add_visit")
     * @param $disciplineId
     * @param $groupId
     * @param $studentId
     * @param $plus
     * @param EntityManagerInterface $em
     * @param Session $session
     * @return Response
     */

    public function addVisit($disciplineId, $groupId, $studentId, $plus, EntityManagerInterface $em, Session $session):Response
    {
        $teacherId = $session->get('teacherId');
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($teacherId);
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->find($disciplineId);
        $student =  $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        $plus = $this->getDoctrine()->getRepository(Plus::class)->findOneBy(['operation' => $plus]);

        $today = new \DateTime();
        $today->format('Y-m-d');

        $visit = New Visit();
        $visit->setTeacher($teacher);
        $visit->setDiscipline($discipline);
        $visit->setStudent($student);
        $visit->setPlus($plus);
        $visit->setDate($today);

        $em->persist($visit);
        $em->flush();

        $forRender = parent::renderDefault();
        $forRender['disciplineId'] = $disciplineId;
        $forRender['groupId'] = $groupId;
        $forRender['title'] = 'Группа - ';
        return $this->redirectToRoute('list_students',$forRender);
    }

    /**
     * @Route ("/user/teacher/discipline/{disciplineId}/student/{studentId}/", name="teacher_statistic")
     * @param $disciplineId
     * @param $studentId
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function statistic($disciplineId, $studentId, EntityManagerInterface $em):Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        $user = $student->getStudentId();
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->find($disciplineId);
        $statistic = $this->getDoctrine()->getRepository(Visit::class)->findStatisticForTeacher($disciplineId, $studentId);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Статистика студента по дисциплине - ';
        $forRender['user'] = $user;
        $forRender['discipline'] = $discipline;
        $forRender['statistics'] = $statistic;
        $forRender['disciplineId'] = $disciplineId;
        $forRender['studentId'] = $studentId;
        return $this->render('main/authorized/teacher/statistic.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/discipline/{disciplineId}/student/{studentId}/deleteVisit/{id}", name="delete_visit")
     * @param $disciplineId
     * @param $studentId
     * @param $id
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function deleteVisit($disciplineId, $studentId, $id, EntityManagerInterface $em):Response
    {
        $visit = $em->getRepository(Visit::class)->find($id);

        $em->remove($visit);
        $em->flush();

        $forRender = parent::renderDefault();
        $forRender['disciplineId'] = $disciplineId;
        $forRender['studentId'] = $studentId;
        return $this->redirectToRoute('teacher_statistic', $forRender);
    }
}