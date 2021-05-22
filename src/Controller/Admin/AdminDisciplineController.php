<?php


namespace App\Controller\Admin;

use App\Entity\Discipline;
use App\Form\DisciplineFromType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDisciplineController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/discipline", name="admin_discipline")
     * @return Response
     */

    public function index()
    {
        $discipline = $this->getDoctrine()->getRepository(Discipline::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Дисциплины';
        $forRender['disciplines'] = $discipline;
        //dd($discipline);
        return $this->render('admin/discipline/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/discipline/create", name="admin_discipline_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function createDiscipline(Request $request, EntityManagerInterface $em)
    {
        $discipline = new Discipline();
        $form = $this->createForm(DisciplineFromType::class, $discipline);
        $form->handleRequest($request);

        if(($form->isSubmitted()) and ($form->isValid()))
        {
            $string = $form->get('name_discipline')->getData();
            $discipline->setName_discipline($string);
            $em->persist($discipline);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Дисциплина создана!');
            return $this->redirectToRoute('admin_discipline');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание дисциплины';
        $forRender['form'] = $form->createView();
        return $this->render('admin/discipline/form.html.twig',$forRender);
    }

    /**
     * @Route("/admin/discipline/delete/{id}", name="admin_discipline_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteDiscipline(Discipline $discipline, EntityManagerInterface $em):Response
    {
        $em->remove($discipline);
        $em->flush();
        return $this->redirectToRoute('admin_discipline');
    }

    /**
     * @Route("/admin/discipline/edit/{id}", name="admin_discipline_edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function editDiscipline(Discipline $discipline, Request $request, EntityManagerInterface $em) :Response
    {
        $form = $this->createForm(DisciplineFromType::class,$discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $string = $form->get('name_discipline')->getData();
            $discipline->setName_discipline($string);

            $em->persist($discipline);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Данные изменены!');
            return $this->redirectToRoute('admin_discipline');
        }
        return $this->render('admin/discipline/editDiscipline.html.twig', ['form' => $form->createView()]);
    }
}