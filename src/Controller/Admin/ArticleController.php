<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\ArticleCenter;
use App\Entity\ArticleTop;
use App\Form\ArticleCenterFormType;
use App\Form\ArticleFormType;
use App\Form\ArticleTopFormType;
use App\Repository\ArticleCenterRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTopRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'admin-article')]
    public function adminArticle(ArticleRepository $articleRepo, ArticleTopRepository $artTopRepo, ArticleCenterRepository $artCenterRepository): Response
    {

        return $this->render('Admin/article/admin-article.html.twig', [
            'articles' => $articleRepo->findAll(),
            'articleTops' =>$artTopRepo->findAll(),
            'articleCenters' =>$artCenterRepository->findAll(),
        ]);
    }

    #[Route('/admin/article/create', name: 'admin-article-create')]
    public function ArticleCreate(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('picture')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setpicture($newFilename);
            }
                $em = $doctrine->getManager();
                $em->persist($article);
                $em->flush();

                $article->setPicture(
                    new File($this->getParameter('picture_directory').'/'.$article->getPicture())
                );

            return $this->redirectToRoute('admin-article');
        }
        
        return $this->render('Admin/article/admin-article-create.html.twig', [
            'articles' => $form->createView(),
        ]);
    }

    #[Route('/admin/article/edit{id}', name: 'admin-article-edit')]
    public function ArticleEdit(SluggerInterface $slugger, $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $art = $doctrine->getRepository(Article::class);
        $article = $art->findOneBy(['id'=>$id]);
        $form = $this->createForm(ArticleFormType::class, $article);

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
                $article->setPicture($newFilename);
        }

            $em = $doctrine->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin-article');
        }

        return $this->render('Admin/article/admin-article-edit.html.twig', [
            'articles' => $form->createView(),
        ]);
    }

    #[Route('/admin/article/delete/{id}', name: 'admin-article-delete')]
    public function productDelete(Article $article, ManagerRegistry $doctrine): RedirectResponse
    {
            $em = $doctrine->getManager();
            $em->remove($article);
            $em->flush();

        return $this->redirectToRoute("admin-article");
    }

    #[Route('admin/article/top/create', name:'admin-article-top-create')]
    public function articleTopCreate(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine)
    {
        $article = new ArticleTop;
        $form = $this->createForm(ArticleTopFormType::class);
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
                $article->setPicture($newFilename);

                $em = $doctrine->getManager();
                $em->persist($article);
                $em->flush();

            }
        return $this->render('admin/article/admin-article-top-create.html.twig',[
            'articleTop' => $form->createView()
        ]);
    }
    #[Route('admin/article/top/edit/{id}', name:'admin-article-top-edit')]
    public function articleTopEdit(SluggerInterface $slugger, $id, ManagerRegistry $doctrine, Request $request)
    {
        $articleRepo = $doctrine->getRepository(ArticleTop::class);
        $article = $articleRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(ArticleTopFormType::class);

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
                $article->setPicture($newFilename);
        }
                $em = $doctrine->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('admin-article');
        }
        return $this->render('admin/article/admin-article-top-edit.html.twig', [
            'articleTop' => $form->createView()
        ]);
    }
    #[Route('admin/article/top/delete', name:'admin-article-top-delete')]
    public function articleTopDelete(ArticleTop $article, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute("admin-article");
    }

    #[Route('admin/article/center/create', name:'admin-article-center-create')]
    public function articleCenterCreate(Request $request, ManagerRegistry $doctrine)
    {
        $article = new ArticleCenter;
        $form = $this->createForm(ArticleCenterFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin-article');
        }

        return $this->render('admin/article/admin-article-center-create.html.twig', [
            'articleCenter' => $form->createView()
        ]);
    }
    
    #[Route('admin/article/center/edit/{id}', name:'admin-article-center-edit')]
    public function articleCenterEdit(ManagerRegistry $doctrine, $id, Request $request)
    {
        $art = $doctrine->getRepository(ArticleCenter::class);
        $article = $art->findOneBy(['id'=>$id]);
        $form = $this->createForm(ArticleCenterFormType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin-article');
        }

        return $this->render('admin/article/admin-article-center-edit.html.twig', [
            'articleCenter' => $form->createView()
        ]);
    }
}
