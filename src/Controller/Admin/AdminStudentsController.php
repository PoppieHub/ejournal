<?php


namespace App\Controller\Admin;

use App\Entity\Student;
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
        //dd($student);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Студенты';
        $forRender['students'] = $student;

        return $this->render('admin/students/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/student/delete/{id}", name="admin_student_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteStudent(Student $student, EntityManagerInterface $em):Response
    {
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute('admin_students');
    }

}