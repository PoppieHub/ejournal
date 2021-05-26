<?php


namespace App\Controller\Main;

use App\Entity\Discipline;
use App\Entity\Plus;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TeacherController extends BaseController
{
    /**
     * @Route ("/user/teacher/", name="list_disciplines")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function listDisciplines(EntityManagerInterface $em):Response
    {
        $user = $this->getUser()->getId();
        $teacher = $em->getRepository('App:Teacher')->findOneBy(['teacher' => $user]);
        $id = $teacher->getId();
        $disciplines =  $em->getRepository('App:Visit')->findDisciplinesForTeacher($id);

        $forRender = parent::renderDefault();
        $forRender['disciplines'] = $disciplines;

        return $this->render('main/authorized/teacher/disciplines.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/{teacherId}/discipline/{disciplineId}", name="list_groups")
     * @param $teacherId
     * @param $disciplineId
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function listGroups($teacherId,$disciplineId,EntityManagerInterface $em):Response
    {
        $groupsAll = $em->getRepository('App:Group')->findAll();
        $groups = $em->getRepository('App:Visit')->findGroupsForTeacher($teacherId,$disciplineId);

        $forRender = parent::renderDefault();
        $forRender['groups'] = $groups;
        $forRender['groupsAll'] = $groupsAll;
        $forRender['teacherId'] = $teacherId;
        $forRender['disciplineId'] = $disciplineId;

        return $this->render('main/authorized/teacher/groups.html.twig', $forRender);
    }

    /**
     * @Route ("/user/teacher/{teacherId}/discipline/{disciplineId}/group/{groupId}", name="list_students")
     * @param $teacherId
     * @param $disciplineId
     * @param $groupId
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function listStudents($teacherId, $disciplineId, $groupId, EntityManagerInterface $em):Response
    {
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
     * @Route ("/user/teacher/{teacherId}/discipline/{disciplineId}/group/{groupId}/student/{studentId}/plus/{plus}/", name="add_visit")
     * @param $teacherId
     * @param $disciplineId
     * @param $groupId
     * @param $studentId
     * @param $plus
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function addVisit($teacherId, $disciplineId, $groupId, $studentId, $plus, EntityManagerInterface $em):Response
    {
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
        $forRender['teacherId'] = $teacherId;
        $forRender['disciplineId'] = $disciplineId;
        $forRender['groupId'] = $groupId;
        $forRender['title'] = 'Группа - ';
        return $this->redirectToRoute('list_students',$forRender);
    }

    /**
     * @Route ("/user/teacher/{teacherId}/discipline/{disciplineId}/student/{studentId}/", name="teacher_statistic")
     * @param $teacherId
     * @param $disciplineId
     * @param $studentId
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function statistic($teacherId, $disciplineId, $studentId, EntityManagerInterface $em):Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        $user = $student->getStudentId();
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->find($disciplineId);
        $statistic = $this->getDoctrine()->getRepository(Visit::class)->findStatisticForTeacher($disciplineId, $studentId);
        $nowTime =  new \DateTime();
        //dd($nowTime);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Статистика студента по дисциплине - ';
        $forRender['user'] = $user;
        $forRender['discipline'] = $discipline;
        $forRender['statistics'] = $statistic;
        $forRender['nowTime '] = $nowTime;
        return $this->render('main/authorized/teacher/statistic.html.twig', $forRender);
    }
}