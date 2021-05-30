<?php


namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\Visit;
use App\Form\CreateGroupFromType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGroupController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/group", name="admin_group")
     * @return Response
     */

    public function index()
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->findBy(
            array(),
            array('group_name' => 'ASC')
        );

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Группы';
        $forRender['groups'] = $group;
        //dd($group);
        return $this->render('admin/group/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/group/create", name="admin_group_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function createGroup(Request $request, EntityManagerInterface $em)
    {
        $groups = new Group();
        $form = $this->createForm(CreateGroupFromType::class, $groups);
        $form->handleRequest($request);

        if(($form->isSubmitted()) and ($form->isValid()))
        {
            //dd($groups);
            $em->persist($groups);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Группа создана!');
            return $this->redirectToRoute('admin_group');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание группы';
        $forRender['form'] = $form->createView();
        return $this->render('admin/group/form.html.twig',$forRender);
    }

    /**
     * @Route("/admin/group/delete/{id}", name="admin_group_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteGroup(Group $group, EntityManagerInterface $em):Response
    {
        $students = $em->getRepository('App:Student')->findBy(['group' => $group->getId()]);

        foreach ($students as &$student){
            $em->getRepository(Visit::class)->deleteStudentVisit($student);
            $student->setGroupId(null);
            $em->persist($student);
            $em->flush();
        }
        $em->remove($group);
        $em->flush();

        $this->addFlash(self::FLASH_INFO, 'Группа удалена и студенты исключены из нее');
        return $this->redirectToRoute('admin_group');
    }

    /**
     * @Route("/admin/group/edit/{id}", name="admin_group_edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function editGroup(Group $group, Request $request, EntityManagerInterface $em) :Response
    {
        $form = $this->createForm(CreateGroupFromType::class,$group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($group);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Данные изменены!');
            return $this->redirectToRoute('admin_group');
        }
        return $this->render('admin/group/editGroup.html.twig', ['form' => $form->createView()]);
    }
}