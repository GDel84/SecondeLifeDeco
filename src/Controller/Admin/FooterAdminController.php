<?php

namespace App\Controller\Admin;

use App\Entity\Footer;
use App\Form\FooterFormType;
use App\Repository\FooterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterAdminController extends AbstractController
{
    #[Route('/admin/footer', name: 'admin-footer')]
    public function index(FooterRepository $footerRepo): Response
    {
        return $this->render('admin/footer/footer-admin.html.twig', [
            'footers'=>$footerRepo->findAll(),
        ]);
    }
    #[Route('/admin/footer/create', name: 'admin-footer-create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $footer = new Footer;
        $form = $this->createForm(FooterFormType::class, $footer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $em->persist($footer);
            $em->flush();

            return $this->redirectToRoute('admin-footer');
        }

        return $this->render('admin/footer/footer-admin-create.html.twig', [
            'footer'=> $form->createView(),
        ]);
    }
    #[Route('/admin/footer/edit/{id}', name: 'admin-footer-edit')]
    public function edit($id, Request $request, FooterRepository $footerRepo, ManagerRegistry $doctrine): Response
    {
        $footerRepo = $doctrine->getRepository(footer::class);
        $footer = $footerRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(FooterFormType::class, $footer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $em->persist($footer);
            $em->flush();

            return $this->redirectToRoute('admin-footer');
        }

        return $this->render('admin/footer/footer-admin-edit.html.twig', [
            'footer'=> $form->createView(),
        ]);
    }
}
