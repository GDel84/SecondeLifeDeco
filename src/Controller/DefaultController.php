<?php

namespace App\Controller;

use App\Repository\AccueilTopRepository;
use App\Repository\ArticleCenterRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(AccueilTopRepository $accueilTopRepo): Response
    {
        return $this->render('accueil.html.twig', [
            'accueilTops' => $accueilTopRepo->findAll(),
        ]);
    }
    #[Route('/article', name: 'article')]
    public function article(ArticleRepository $articleRepo, ArticleTopRepository $artTopRepo, ArticleCenterRepository $artCenterRepo): Response
    {
        return $this->render('article.html.twig', [
            'article' => $articleRepo->findAll(),
            'articleTops' =>$artTopRepo->findAll(),
            'articleCenters' =>$artCenterRepo->findAll(),
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
