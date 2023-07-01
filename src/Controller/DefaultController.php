<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\AccueilCenterRepository;
use App\Repository\AccueilTopRepository;
use App\Repository\ArticleCenterRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTopRepository;
use App\Repository\FooterRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceTopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(AccueilTopRepository $accueilTopRepo, SessionInterface $session, FooterRepository $footerRepo, AccueilCenterRepository $accueilCenterRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('accueil.html.twig', [
            'accueilTops' => $accueilTopRepo->findAll(),
            'accueilCenters' => $accueilCenterRepo->findAll(),
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
    public function contact(ArticleTopRepository $articleTopRepo, AccueilTopRepository $accueilTopRepo, SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('contact.html.twig', [
        'accueilTops' => $accueilTopRepo->findAll(),
        'articleTops' => $articleTopRepo->findAll(),
        'nombreArticlesPanier' => $nombreArticlesPanier,
        'footers' => $footerRepo->findAll(),
        ]);

    }
    #[Route('/service', name: 'service')]
    public function service(ServiceRepository $serviceRepo, ServiceTopRepository $serviceTopRepo, SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('service.html.twig', [
        'serviceTops' => $serviceTopRepo->findAll(),
        'services' => $serviceRepo->findAll(),
        'nombreArticlesPanier' => $nombreArticlesPanier,
        'footers' => $footerRepo->findAll(),
        ]);

    }
    #[Route('/mention', name: 'mention')]
    public function mentionLegale(ArticleTopRepository $articleTopRepo, AccueilTopRepository $accueilTopRepo, SessionInterface $session, FooterRepository $footerRepo): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        return $this->render('mentionLegale.html.twig', [
        'accueilTops' => $accueilTopRepo->findAll(),
        'articleTops' => $articleTopRepo->findAll(),
        'nombreArticlesPanier' => $nombreArticlesPanier,
        'footers' => $footerRepo->findAll(),
        ]);

    }
}
