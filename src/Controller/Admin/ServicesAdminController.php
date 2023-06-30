<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\ServiceTop;
use App\Form\ServiceFormType;
use App\Form\ServiceTopFormType;
use App\Repository\ServiceRepository;
use App\Repository\ServiceTopRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ServicesAdminController extends AbstractController
{
    #[Route('/admin/service', name: 'admin-service')]
    public function index(ServiceRepository $serviceRepo, ServiceTopRepository $serviceTopRepo): Response
    {
        return $this->render('/admin/service/admin-service.html.twig', [
            'services' => $serviceRepo->findAll(),
            'serviceTops' =>$serviceTopRepo->findAll(),
        ]);
    }
    #[Route('/admin/service/top/create', name: 'admin-service-top-create')]
    public function serviceTopCreate(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $service = new ServiceTop();
        $form = $this->createForm(ServiceTopFormType::class, $service);
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
                $service->setPicture($newFilename);

                $em = $doctrine->getManager();
                $em->persist($service);
                $em->flush();

                return $this->redirectToRoute('admin-service');

            }

        return $this->render('/admin/service/admin-service-top-create.html.twig', [
            'serviceTop' => $form->createView(),
        ]);
    }
    #[Route('/admin/service/top/edit{id}', name: 'admin-service-top-edit')]
    public function serviceTopEdit(SluggerInterface $slugger, $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $serviceRepo = $doctrine->getRepository(ServiceTop::class);
        $serviceTop = $serviceRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(ServiceTopFormType::class, $serviceTop);

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
                $serviceTop->setPicture($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($serviceTop);
            $em->flush();

            return $this->redirectToRoute('admin-accueil');
        }
        return $this->render('/admin/service/admin-service-top-edit.html.twig', [
            'serviceTop' => $form->createView(),
        ]);
    }
    #[Route('/admin/service/top/delete', name: 'admin-service-top-delete')]
    public function serviceTopDelete(ManagerRegistry $doctrine, ServiceTop $service): RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($service);
        $em->flush();

        return $this->redirectToRoute('admin-service');
    }
    #[Route('/admin/service/create', name: 'admin-service-create')]
    public function serviceCreate(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceFormType::class, $service);
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
                $service->setPicture($newFilename);

                $em = $doctrine->getManager();
                $em->persist($service);
                $em->flush();

                return $this->redirectToRoute('admin-service');

            }

        return $this->render('/admin/service/admin-service-create.html.twig', [
            'service' => $form->createView(),
        ]);
    }
    #[Route('/admin/service/edit{id}', name: 'admin-service-edit')]
    public function serviceEdit(SluggerInterface $slugger, $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $serviceRepo = $doctrine->getRepository(ServiceTop::class);
        $service = $serviceRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(ServiceFormType::class, $service);

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
                $service->setPicture($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('admin-service');
        }

        return $this->render('/admin/service/admin-service-edit.html.twig', [
            'service' => $form->createView(),
        ]);
    }
    #[Route('/admin/service', name: 'admin-service-delete')]
    public function serviceDelete(ManagerRegistry $doctrine, Service $service): RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($service);
        $em->flush();

        return $this->redirectToRoute('admin-service');
    }
}
