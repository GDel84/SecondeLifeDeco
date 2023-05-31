<?php

namespace App\Controller\Admin;

use App\Entity\AccueilTop;
use App\Form\AccueilTopFormType;
use App\Repository\AccueilTopRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AccueilController extends AbstractController
{
    #[Route('/admin/accueil', name: 'admin-accueil')]
    public function index(AccueilTopRepository $accueilTopRepo): Response
    {
        return $this->render('admin/admin-accueil.html.twig', [
            'accueilTops' => $accueilTopRepo->findAll(),
        ]);
    }

    #[Route('/admin/accueil/top/create', name: 'admin-accueil-top-create')]
    public function accueilTopCreate(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine)
    {
        $accueil = new AccueilTop();
        $form = $this->createForm(AccueilTopFormType::class, $accueil);
        $form->handleRequest($request);

        $pictureFile = $form->get('picture')->getData();

        // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $accueil->setPicture($newFilename);

                $em = $doctrine->getManager();
                $em->persist($accueil);
                $em->flush();

                return $this->redirectToRoute('admin-accueil');

            }
        return $this->render('admin/accueil/admin-accueil-top-create.html.twig', [
            'accueilTop' => $form->createView()
        ]);
        
    }
    #[Route('/admin/accueil/top/edit/{id}', name:'admin-accueil-top-edit')]
    public function AccueilTopEdit(SluggerInterface $slugger, $id, ManagerRegistry $doctrine, Request $request)
    {
        $accueilRepo = $doctrine->getRepository(AccueilTop::class);
        $accueilTop = $accueilRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(AccueilTopFormType::class, $accueilTop);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $photofile = $form->get('picture')->getData();

            if ($photofile) {
                $originalFilename = pathinfo($photofile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photofile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photofile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $accueilTop->setPicture($newFilename);
        }
            $em = $doctrine->getManager();
            $em->persist($accueilTop);
            $em->flush();

            return $this->redirectToRoute('admin-accueil');
        }

        return $this->render('admin/accueil/admin-accueil-top-edit.html.twig', [
            'accueilTop' => $form->createView()
        ]);
    }

    #[Route('/admin/accueil/top/delete/{id}', name: 'admin-accueil-top-delete')]
    public function accueilDelete(AccueilTop $accueilTop, ManagerRegistry $doctrine): RedirectResponse
    {
            $em = $doctrine->getManager();
            $em->remove($accueilTop);
            $em->flush();

        return $this->redirectToRoute("admin-accueil");
    }
}
