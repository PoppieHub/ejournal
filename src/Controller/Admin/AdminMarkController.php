<?php


namespace App\Controller\Admin;

use App\Entity\Plus;
use App\Entity\Visit;
use App\Form\MarkFromType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMarkController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/mark", name="admin_mark")
     * @return Response
     */

    public function index()
    {
        $mark = $this->getDoctrine()->getRepository(Plus::class)->findBy(
            array(),
            array('id' => 'ASC')
        );

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Отметки';
        $forRender['marks'] = $mark;
        //dd($discipline);
        return $this->render('admin/mark/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/mark/create", name="admin_mark_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function createMark(Request $request, EntityManagerInterface $em)
    {
        $mark = new Plus();
        $form = $this->createForm(MarkFromType::class, $mark);
        $form->handleRequest($request);

        if(($form->isSubmitted()) and ($form->isValid()))
        {
            $string = $form->get('operation')->getData();
            $mark->setOperation($string);
            //dd($mark);
            $em->persist($mark);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Отметка создана!');
            return $this->redirectToRoute('admin_mark');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание отметки';
        $forRender['form'] = $form->createView();
        return $this->render('admin/mark/form.html.twig',$forRender);
    }

    /**
     * @Route("/admin/mark/delete/{id}", name="admin_mark_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteMark(Plus $mark, EntityManagerInterface $em):Response
    {
        $em->getRepository(Visit::class)->deleteMarkVisit($mark->getId());
        $em->remove($mark);
        $em->flush();
        $this->addFlash(self::FLASH_INFO, 'Отметка и записи с ней удалены!');
        return $this->redirectToRoute('admin_mark');
    }

    /**
     * @Route("/admin/mark/edit/{id}", name="admin_mark_edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function editMark(Plus $mark, Request $request, EntityManagerInterface $em) :Response
    {
        $form = $this->createForm(MarkFromType::class,$mark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $string = $form->get('operation')->getData();
            $mark->setOperation($string);

            $em->persist($mark);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Данные изменены!');
            return $this->redirectToRoute('admin_mark');
        }
        return $this->render('admin/mark/editMark.html.twig', ['form' => $form->createView()]);
    }

}