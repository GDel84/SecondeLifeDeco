<?php

namespace App\Services;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierServices{

    private $session;

    public function __construct(
        private RequestStack $sessionsrv, 
        private ArticleRepository $ArticleRepo,
    )
    {
        $this->session = $this->sessionsrv->getSession();
    }

    public function addPanier($id, $qt){

        $panier = $this->session->get("panier", []);

        $article = $this->ArticleRepo->findOneBy(['id'=>$id]);
        $id = $article->getId();
        $max = $article->getQuantity();

        if(!empty($panier[$id])){
            $total = $panier[$id]+intval($qt);
            if(intval($total)>intval($max)){
                $total = $max;
            }
        }else{
            $total = intval($qt);
            if($total>intval($max)){
                $total = $max;
            }
        }
        $panier[$id] = $total;

        // sauvegrade dans la session
        $this->session->set("panier", $panier);
    }

    public function removePanier($id){
        $panier = $this->session->get("panier", []);

        $article = $this->ArticleRepo->findOneBy(['id'=>$id]);
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
        $this->session->set("panier", $panier);

    }

    public function deletePanier($id){
        $session = $this->sessionsrv->getSession();

        $panier = $this->session->get("panier", []);

        $article = $this->ArticleRepo->findOneBy(['id'=>$id]);
        $id = $article->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // sauvegrade dans la session
        $this->session->set("panier", $panier);

    }

}