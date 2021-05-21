<?php

namespace App\Controller\Security;

use App\Form\ChangePasswordFormType;
use App\Form\EditPersonalInformationFormType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const FLASH_INFO = 'info';

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/user/profile/change-personal", name="change_personal")
     */
    public function ChangePersonalInfo(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em) :Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditPersonalInformationFormType::class , $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Данные изменены!');
            return $this->redirectToRoute('_profile');
        }
        return $this->render('security/changePersonalInfo.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/profile/change-password", name="change_password")
     */
    public function ChangePassword(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em) :Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class , $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Пароль изменен!');
            return $this->redirectToRoute('_profile');
        }
        return $this->render('security/changePassword.html.twig', ['form' => $form->createView()]);
    }
}
