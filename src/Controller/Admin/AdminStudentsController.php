<?php


namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStudentsController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/students", name="admin_students")
     * @return Response
     */

    public function index(EntityManagerInterface $em)
    {
        $student = $em->getRepository('App:Student')->findStudent();
        $group = $em->getRepository('App:Group')->findAll();
        //dd($student);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Студенты';
        $forRender['students'] = $student;
        $forRender['groups'] = $group;

        return $this->render('admin/students/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/student/delete/{id}", name="admin_student_delete")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteStudent($id, EntityManagerInterface $em):Response
    {
        $student = $em->getRepository(Student::class)->find($id);
        $student->setGroupId(null);
        $em->getRepository(Visit::class)->deleteStudentVisit($student->getId());
        $em->persist($student);
        $em->flush();
        $em->getRepository('App:Student')->deleteStudent($id);
        $this->addFlash(self::FLASH_INFO, 'Роль студента удалена!');
        return $this->redirectToRoute('admin_students');
    }

    /**
     * @Route("/admin/student/{student}/group/{group}", name="admin_student_setGroup")
     * @param $student
     * @param $group
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function setGroup($student, $group, EntityManagerInterface $em):Response
    {
        $setGroup = $em->getRepository('App:Student')->setGroup($group, $student);

        //dd($setGroup);
        $this->addFlash(self::FLASH_INFO, 'Группа обновлена!');

        return $this->redirectToRoute('admin_students');
    }
}