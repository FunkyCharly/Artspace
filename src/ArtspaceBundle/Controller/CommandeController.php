<?php

namespace ArtspaceBundle\Controller;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ArtspaceBundle\Entity\Commande;
use ArtspaceBundle\Entity\Detailcommande;
use Symfony\Component\HttpFoundation\Response;


class CommandeController extends Controller
{
    public function commandeSaveAction()
    {
        $user = $this->getUser();
        if (empty($user))
        {
            return $this->redirect($this->generateUrl('login'));
        }else{
    $commande = new Commande();
    $commande->setDatecreated( new \DateTime() );
    $commande->setUsername($user->getUsername());
    $commande->setFirstname($user->getFirstname());
    $commande->setAdresse($user->getAddress());
    $commande->setEmail($user->getEmail());
    $commande->setStatut('ok');
    
    $commande->setUser($user);
    $em = $this->getDoctrine()->getManager();
    $em->persist($commande);
    
    
    $session = new Session();
    $products = $session->get('panier');
    $total = 0;
    foreach( $products as $p){
        
        
    $detailcommande = new Detailcommande();
    $detailcommande->setCategorie( $p->getCategorie() );
    $detailcommande->setPrix($p->getPrix());
    
    $total+= $p->getPrix();
    
    $detailcommande->setCommande($commande);
    $em->persist($detailcommande);
    }
    $commande->setTotal($total);
    $em->flush();
    $repository = $this->getDoctrine()
    ->getManager()
    ->getRepository('ArtspaceBundle:Commande');
    $panier = Array();           
        $session->set('panier', $panier);
    
      return $this->redirect($this->generateUrl('commande_recapitulatif'));

    }
    }
    public function commandeViewAction(){
        $user = $this->getUser();
        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('ArtspaceBundle:Commande');
        
        $commande = $repository->findByUser($user);
        
        $params = array(
        'commande' => $commande,
                );
                
        return $this->render("ArtspaceBundle:commande:recap.html.twig", $params);
    }
    
}
