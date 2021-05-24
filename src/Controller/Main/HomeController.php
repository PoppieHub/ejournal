<?php


namespace App\Controller\Main;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        return $this->render('main/index.html.twig', $forRender);
    }

    #[Route('/user/profile', name: '_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $student = $this->getDoctrine()->getRepository('App:Student')->findOneBy(['student' => $id]);
        $teacher = $this->getDoctrine()->getRepository('App:Teacher')->findOneBy(['teacher' => $id]);

        return $this->render('main/authorized/profile.html.twig', ['user' => $user, 'student' => $student, 'teacher' => $teacher]);
    }

    #[Route('/user/baseProfile', name: '_BaseProfile')]
    public function baseProfile(): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $student = $this->getDoctrine()->getRepository('App:Student')->findOneBy(['student' => $id]);
        $teacher = $this->getDoctrine()->getRepository('App:Teacher')->findOneBy(['teacher' => $id]);

        return $this->render('main/authorized/baseProfile.html.twig', ['student' => $student, 'teacher' => $teacher]);
    }

}