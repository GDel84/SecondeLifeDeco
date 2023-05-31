<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Panier;
use App\Form\PanierFormType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Handler\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function panier(Request $request, ManagerRegistry $doctrine, ArticleRepository $articleRepo): Response
    {
        $panier = new Panier;
        $form = $this->createForm(PanierFormType::class, $panier);
        $form->handleRequest($request);

        $em = $doctrine->getManager();
        $em->persist($panier);
        $em->flush();


        return $this->render('panier.html.twig', [
            'panier' => $form->createView(),
            'article'=> $articleRepo->findOneBy(array(), array())
        ]);
    }
    #[Route('/panier', name:'panier')]
    public function test(SessionInterface $session, ArticleRepository $articleRepo)
    {
        $panier = $session->get("panier", []);

        $dataPanier = [];

        foreach($panier as $id => $quantity){
            $article = $articleRepo->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite" => $quantity
            ];

        }
        return $this->render('panier.html.twig', compact("dataPanier"));
    }

    #[Route('/add/{id}', name :'add')]
    public function add(Article $article, $id, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $article->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // sauvegrade dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }
    #[Route('/remove/{id}', name :'remove')]
    public function remove(Article $article, $id, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $article->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
        }else{
            unset($panier[$id]);
        }
        }else{
            $panier[$id] = 1;
        }

        // sauvegrade dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }
    #[Route('/delete/{id}', name :'delete')]
    public function delete(Article $article, $id, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $article->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // sauvegrade dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    
}
