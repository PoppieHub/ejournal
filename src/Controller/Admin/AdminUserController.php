<?php


namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Form\EditUserForAdminFromType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AdminBaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/admin/user", name="admin_user")
     * @return Response
     */

    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Пользователи';
        $forRender['users'] = $users;
        //dd($users);
        return $this->render('admin/user/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */

    public function createUser(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if(($form->isSubmitted()) and ($form->isValid()))
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Админ-пользователь создан!');
            return $this->redirectToRoute('admin_user');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание админ-пользователя';
        $forRender['form'] = $form->createView();
        return $this->render('admin/user/form.html.twig',$forRender);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteUser(User $user, EntityManagerInterface $em):Response
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function editUser(User $user, Request $request, EntityManagerInterface $em) :Response
    {
        $form = $this->createForm(EditUserForAdminFromType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Данные изменены!');
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/user/editUser.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/confirm/{id}", name="admin_user_confirm")
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function confirmUser(User $user, Request $request, EntityManagerInterface $em) :Response
    {
       $thisUser= $em->getRepository(User::class)->find($user);
       $role = $thisUser ->getRoles();
       $roleStudent = 'ROLE_STUDENT';
       $roleAStudent = 'ROLE_ASTUDENT';
       $roleTeacher = 'ROLE_TEACHER';
       $roleATeacher= 'ROLE_ATEACHER';

       $notationStudent = in_array($roleStudent,$role,$strict = false);
       $notationAStudent = in_array($roleAStudent,$role,$strict = false);
       $notationTeacher = in_array($roleTeacher,$role,$strict = false);
       $notationATeacher = in_array($roleATeacher,$role,$strict = false);

       //dd($notationStudent,$notationAStudent,$notationTeacher,$notationATeacher);

       if($notationStudent == true or $notationAStudent == true)
       {
            $student = New Student();
            $student->setStudentId($user);
            $em->persist($student);
            $em->flush();

           $this->addFlash(self::FLASH_INFO, 'Подтверждена роль студента');
       }

       elseif($notationTeacher == true or $notationATeacher == true)
       {
           $teacher = New Teacher();
           $teacher->setTeacher($user);
           $em->persist($teacher);
           $em->flush();

           $this->addFlash(self::FLASH_INFO, 'Подтверждена роль преподавателя');
       }

       else  $this->addFlash(self::FLASH_INFO, 'Произошло исключение');

       return $this->redirectToRoute('admin_user');
    }
}