<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
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
    public function adminArticle(ArticleRepository $articleRepo): Response
    {

        return $this->render('Admin/article/admin-article.html.twig', [
            'articles' => $articleRepo->findAll(),
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
}
