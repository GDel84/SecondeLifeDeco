<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAdminFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends AbstractController
{
    #[Route('/admin/user', name: 'admin-user')]
    public function userAdmin(UserRepository $userRepo): Response
    {
        
        return $this->render('admin/user/userAdmin.html.twig', [
            'users' => $userRepo->findAll(),
        ]);
    }

    #[Route('admin/user/edit/{id}', name: 'admin-user-edit')]
    public function userAdminEdit(ManagerRegistry $doctrine, $id, Request $request)
    {
        $userRepo = $doctrine->getRepository(User::class);
        $user = $userRepo->findOneBy(['id'=>$id]);
        $form = $this->createForm(UserAdminFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin-user');
        }
        return $this->render('admin/user/admin-user-edit.html.twig', [
            'user' => $form->createView()
        ]);
    }
}
