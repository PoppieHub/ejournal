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
        //dd($user);
        return $this->render('main/authorized/profile.html.twig', ['user' => $user]);
    }

}