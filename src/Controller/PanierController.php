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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function test(Request $request, SessionInterface $session, ArticleRepository $articleRepo, MailerInterface $mailer)
    {
        //recuperation du formulaire panier
        $panier = new Panier;
        $form = $this->createForm(PanierFormType::class, $panier);
        $form->handleRequest($request);

        //mettre en memoir dans session les article choisie dans panier
        $panier = $session->get("panier", []);

        $dataPanier = [];

        //compter le nombre d'article dans le panier
        $nombreArticlesPanier = count($panier);

        foreach($panier as $id => $quantity){
            $article = $articleRepo->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite" => $quantity
            ];
        }
    
        // Construire le contenu de l'e-mail
        $message = $this->renderView('email/panier.html.twig', ['dataPanier' => $dataPanier]);
    
        // Créer l'objet Email
        $email = (new Email())
            ->from('test@mail.fr')
            ->to('g-del@hotmail.fr')
            ->subject('Devis pour le panier')
            ->html($message);
    
        // Envoyer l'e-mail
        $mailer->send($email);

        return $this->render('panier.html.twig', [
            'dataPanier' => $dataPanier,
            'nombreArticlesPanier' => $nombreArticlesPanier,
        ]);
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
