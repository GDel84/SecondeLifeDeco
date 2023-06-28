<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use App\Form\DevisAdminFormType;
use App\Form\DevisFormType;
use App\Repository\DevisRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DevisAdminController extends AbstractController
{
    #[Route('/admin/devis', name: 'admin-devis')]
    public function index(DevisRepository $devisRepo): Response
    {
        return $this->render('admin/devis/devis-admin.html.twig', [
            'deviss'=> $devisRepo->findAll(),
        ]);
    }
    #[Route('/admin/devis/create', name: 'admin-devis-create')]
    public function createDevis(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $devis = New Devis;
        $form = $this->createForm(DevisFormType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($devis);
            $em->flush();

            return $this->redirectToRoute('admin-devis');
        }
        
        return $this->render('Admin/devis/devis-admin-create.html.twig', [
            'devis' => $form->createView(),
        ]);
    }

    #[Route('/admin/devis/edit{id}', name: 'admin-devis-edit')]
    public function devisEdit(DevisRepository $devisRepo, ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $devisRepo = $doctrine->getRepository(Devis::class);
        $devis = $devisRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(DevisAdminFormType::class, $devis);
        dump($devis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em = $doctrine->getManager();
            $em->persist($devis);
            $em->flush();

            return $this->redirectToRoute('admin-devis');
        }

        return $this->render('admin/devis/devis-admin-edit.html.twig', [
            'devis'=> $form->createView(),
        ]);
    }

    #[Route('/admin/devis/delete/{id}', name: 'admin-devis-delete')]
    public function devisDelete(Devis $devis, ManagerRegistry $doctrine): RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($devis);
        $em->flush();

        return $this->redirectToRoute('admin-devis');
    }
}
