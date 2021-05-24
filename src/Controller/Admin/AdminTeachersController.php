<?php


namespace App\Controller\Admin;

use App\Entity\Discipline;
use App\Entity\Teacher;
use App\Entity\Visit;
use App\Form\AdminVisitFormType;
use App\Form\VisitForMakeDisciplineFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTeachersController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/teachers", name="admin_teachers")
     * @return Response
     */

    public function index(EntityManagerInterface $em)
    {

        $teacher  = $em->getRepository('App:Teacher')->findAll();

        //dd($teacher);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Преподаватели';
        $forRender['teachers'] = $teacher;

        return $this->render('admin/teacher/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/teachers/{id}/delete", name="admin_teacher_delete")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteTeacher($id, EntityManagerInterface $em):Response
    {
        $em->getRepository('App:Teacher')->deleteTeacher($id);

        return $this->redirectToRoute('admin_teachers');
    }

    /**
     * @Route("/admin/teachers/{id}/", name="admin_teacher_discipline")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function viewDisciplines($id, EntityManagerInterface $em):Response
    {
        $query = $em->getRepository('App:Teacher')->findDisciplines($id);
        $queryAll = $this->getDoctrine()->getRepository(Discipline::class)->findBy(
            array(),
            array('name_discipline' => 'ASC')
        );

        $forRender = parent::renderDefault();
        $forRender['disciplines'] = $query;
        $forRender['disciplinesAll'] = $queryAll;
        $forRender['id'] = $id;
        $forRender['title'] = 'Список дисциплин для';
        return $this->render('admin/teacher/disciplines.html.twig', $forRender);
    }

    /**
     * @Route("/admin/teachers/{id}/createVisit/{disciplineId}", name="admin_create_visit")
     * @param $id
     * @param $disciplineId
     * @return RedirectResponse|Response
     */

    public function createVisit($id,$disciplineId, EntityManagerInterface $em)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->find($disciplineId);
        $today = new \DateTime();
        $today->format('Y-m-d');

        $visit = New Visit();
        $visit->setTeacher($teacher);
        $visit->setDiscipline($discipline);
        $visit->setDate($today);

        $em->persist($visit);
        $em->flush();

        $forRender['id'] = $id;
        $this->addFlash(self::FLASH_INFO, 'Дисциплина добавлена!');

        return $this->redirectToRoute('admin_teacher_discipline',$forRender);
    }

    /**
     * @Route("/admin/teachers/{id}/discipline/{disciplineId}/delete", name="admin_teacher_discipline_delete")
     * @param $id
     * @param $disciplineId
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteDiscipline($id, $disciplineId, EntityManagerInterface $em):Response
    {
        $em->getRepository('App:Visit')->deleteDisciplineForTeacher($id,$disciplineId);

        $forRender['id'] = $id;

        $this->addFlash(self::FLASH_INFO, 'Дисциплина удалена!');

        return $this->redirectToRoute('admin_teacher_discipline',$forRender);
    }

}