<?php


namespace App\Controller\Main;

use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function profile(Request $request, FileManagerServiceInterface $fileManagerService, EntityManagerInterface $em): Response
    {
        $thisUser = $this->getUser();
        $id = $thisUser->getId();
        $user = $em->getRepository('App:User')->find($id);
        $student = $this->getDoctrine()->getRepository('App:Student')->findOneBy(['student' => $id]);
        $teacher = $this->getDoctrine()->getRepository('App:Teacher')->findOneBy(['teacher' => $id]);

        $form = $this->createForm('App\Form\UserImageFormType',$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $imageOld = $user->getImage();
            if ($image){
                if($imageOld){
                    $fileManagerService->removeUserImage($imageOld);
                }
                $fileName = $fileManagerService->imageUserUpload($image);
                $user->setImage($fileName);
                $em->persist($user);
                $em->flush();
            }
            return $this->redirectToRoute('_profile');
        }

        $forRender = parent::renderDefault();
        $forRender['user'] =  $user;
        $forRender['student'] =  $student;
        $forRender['teacher'] =  $teacher;
        $forRender['form'] = $form->createView();

        return $this->render('main/authorized/profile.html.twig', $forRender);
    }

    /**
     * @Route("/user/profile/deleteImage/{id}", name="delete_image")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function deleteImage($id ,FileManagerServiceInterface $fileManagerService, EntityManagerInterface $em):Response
    {
        $user = $em->getRepository('App:User')->find($id);
        if($user->getImage() != null){
            $image = $user->getImage();
            $fileManagerService->removeUserImage($image);
            $user->setImage(null);
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('_profile');
    }


}