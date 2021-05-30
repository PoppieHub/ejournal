<?php


namespace App\Controller\Main;

use App\Entity\Teacher;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $forRender = parent::renderDefault();
        return $this->render('main/index.html.twig', $forRender);
    }

    #[Route('/user/profile', name: '_profile')]
    public function profile(Request $request, FileManagerServiceInterface $fileManagerService, EntityManagerInterface $em): Response
    {
        $userId = $this->getUser()->getId();
        $session = $this->get('session');
        $session->set('user',$this->getUser());

        if ($em->getRepository('App:Teacher')->findBy(['teacher' => $userId])){
            $teacherAccess = true;
            $session->set('teacherAccess',$teacherAccess);
        }
        if ($em->getRepository('App:Student')->findBy(['student' => $userId]))
        {
            $studentAccess = true;
            $session->set('studentAccess',$studentAccess);
        }

        $user = $em->getRepository('App:User')->find($userId);

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
        $forRender['form'] = $form->createView();

        return $this->render('main/authorized/profile.html.twig', $forRender);
    }

    /**
     * @Route("/user/profile/deleteImage/", name="delete_image")
     */
    public function deleteImage(FileManagerServiceInterface $fileManagerService, EntityManagerInterface $em, Session $session):Response
    {
        $userId = $session->get('user')->getId();
        $user = $em->getRepository('App:User')->find($userId);
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