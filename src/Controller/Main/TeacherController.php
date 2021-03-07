<?php


namespace App\Controller\Main;

use Symfony\Component\Routing\Annotation\Route;
class TeacherController extends BaseController
{
    /**
     * @Route ("/teacher", name="teacher")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        return $this->render('main/teacher/teacher.html.twig', $forRender);
    }
}