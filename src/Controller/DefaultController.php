<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('accueil.html.twig', [
            
        ]);
    }
    #[Route('/article', name: 'article')]
    public function article(ArticleRepository $articleRepo): Response
    {
        return $this->render('article.html.twig', [
            'article' => $articleRepo->findAll(),
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
