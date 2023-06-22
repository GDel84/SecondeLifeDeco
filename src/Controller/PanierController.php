<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Devis;
use App\Form\DevisFormType;
use App\Form\PanierFormType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/devis', name: 'devis')]
    public function panier(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer, SessionInterface $session): Response
    {
        $devis = new Devis;
        $form = $this->createForm(DevisFormType::class, $devis);
        $form->handleRequest($request);

        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);
        
        $articlerepo = $doctrine->getRepository(Article::class);

        if($form->isSubmitted() && $form->isValid()){
            $texteDevis = "";
            foreach(array_keys($panier) as $element){
                $articleachete = $articlerepo->findOneBy(['id'=>$element]);
                $texteDevis .= "Nom : ".$articleachete->getName()."\n";
                $texteDevis .= "Qt souhaitee : ".$panier[$element]."\n";
            }
            $devis->setCommande($texteDevis);
            $em = $doctrine->getManager();
            $em->persist($devis);
            $em->flush();

            
            $emailCleint = $devis->getEmail();

            // Construire le contenu de l'e-mail
            $message = $this->renderView('email/panier.html.twig', ['devis' => $devis]);

            // Créer l'objet Email
            $email = (new Email())
                ->from($emailCleint)
                ->to($emailCleint)
                ->subject('Devis pour le panier')
                ->html($message);

            $message = $this->renderView('email/panier.html.twig', ['devis' => $devis]);

            // Créer l'objet Email
            $email = (new Email())
                ->from($emailCleint)
                ->to('g-del@hotmail.fr')
                ->subject('Devis pour le panier')
                ->html($message);

            // Envoyer l'e-mail
            $mailer->send($email);

            $session->remove("panier");

            return $this->redirectToRoute('accueil');
        }

        return $this->render('devis.html.twig', [
            'devis' => $form->createView(),
            'nombreArticlesPanier' => $nombreArticlesPanier,
        ]);
    }

    #[Route('/panier', name:'panier')]
    public function test(Request $request, SessionInterface $session, ArticleRepository $articleRepo)
    {

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

        return $this->render('panier.html.twig', [
            'dataPanier' => $dataPanier,
            'nombreArticlesPanier' => $nombreArticlesPanier,
        ]);
    }

    #[Route('/add/{id}', name :'add', methods:["GET","POST"])]
    public function add(Article $article, $id, SessionInterface $session, Request $request)
    {

        $panier = $session->get("panier", []);
        $id = $article->getId();

        if(!empty($panier[$id])){
            $panier[$id] = $panier[$id]+intval($request->get('quantity'));
        }else{
            $panier[$id] = intval($request->get('quantity'));
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
