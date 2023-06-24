<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\FooterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(FooterRepository $footerRepo, AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error,
            'nombreArticlesPanier' => $nombreArticlesPanier,
            'footers' => $footerRepo->findAll(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path:'/account', name:'compte')]
    public function monCompte(FooterRepository $footerRepo, ManagerRegistry $doctrine, Security $security, Request $request, SessionInterface $session):Response
    {
        $panier = $session->get("panier", []);
        $nombreArticlesPanier = count($panier);

        $user = $security->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('security/account.html.twig',[
            'user' => $form->createView(),
            'nombreArticlesPanier' => $nombreArticlesPanier,
            'footers' => $footerRepo->findAll(),
        ]);
    }

    #[Route('/user', name: 'app_user')]
    public function index(FooterRepository $footerRepo, ManagerRegistry $doctrine, Request $request, Security $security, SessionInterface $session): Response
    {
            $panier = $session->get("panier", []);
            $nombreArticlesPanier = count($panier);

        //recuperer le user connecte
        //creer le form type et gerer la soumission
            //persister l'object
            $user = $security->getUser();
            $form = $this->createForm(UserFormType::class, $user);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $user = $form->getData();
                $manager = $doctrine->getManager();
                $manager->persist($user);
                $manager->flush();
                // ... perform some action, such as saving the task to the database

            }

        return $this->render('security/account.html.twig', [
            'user' => $form->createView(),
            'nombreArticlesPanier' => $nombreArticlesPanier,
            'footers' => $footerRepo->findAll(),
        ]);
    }
}

