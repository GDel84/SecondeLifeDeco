<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('accueil.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/article', name: 'article')]
    public function article(): Response
    {
        return $this->render('article.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
