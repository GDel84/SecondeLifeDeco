<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'admin-article')]
    public function index(): Response
    {
        return $this->render('Admin/article/article.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
