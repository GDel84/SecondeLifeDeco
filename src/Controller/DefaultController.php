<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\AccueilTopRepository;
use App\Repository\ArticleCenterRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTopRepository;
use App\Repository\FooterRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(AccueilTopRepository $accueilTopRepo, SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('accueil.html.twig', [
            'accueilTops' => $accueilTopRepo->findAll(),
            'nombreArticlesPanier' => $nombreArticlesPanier,
            'footers' => $footerRepo->findAll(),
        ]);
    }

    #[Route('/article', name: 'article')]
    public function article(ArticleRepository $articleRepo, ArticleTopRepository $artTopRepo, ArticleCenterRepository $artCenterRepo, SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('article.html.twig', [
            'articles' => $articleRepo->findAll(),
            'articleTops' =>$artTopRepo->findAll(),
            'articleCenters' =>$artCenterRepo->findAll(),
            'nombreArticlesPanier' => $nombreArticlesPanier,
            'footers' => $footerRepo->findAll(),
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('contact.html.twig', [
        'nombreArticlesPanier' => $nombreArticlesPanier,
        'footers' => $footerRepo->findAll(),
        ]);
    }
}
