<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\KartoVm;

class HomeController extends Controller
{
    /**
     * @Route("/")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->redirectToRoute('app_home_home');
    }

    /**
     * @Route("/home")
     *
     * @return Response
     */
    public function homeAction() : Response
    {
        return $this->render('home.html.twig', [
            'home' => true,
            'kartovm' => $this->getDoctrine()->getRepository(KartoVm::class)->findAll()
        ]);
    }
}