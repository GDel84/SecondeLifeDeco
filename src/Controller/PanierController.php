<?php

namespace App\Controller;

use App\Form\PanierFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(): Response
    {
        $form = $this->createForm(PanierFormType::class);

        return $this->render('panier/panier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
