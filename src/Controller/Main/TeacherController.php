<?php


namespace App\Controller\Main;

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
        $students = $em->getRepository('App:Visit')->findStudentsFromGroupForTeacher($disciplineId, $groupId);

        $forRender = parent::renderDefault();
        $forRender['teacherId'] = $teacherId;
        $forRender['disciplineId'] = $disciplineId;
        $forRender['students'] = $students;
        return $this->render('main/authorized/teacher/students.html.twig', $forRender);
    }
}