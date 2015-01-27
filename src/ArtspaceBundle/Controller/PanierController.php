<?php

namespace ArtspaceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{

    public function addToPanierAction($id)//devient addToCommande
    {
        $user = $this->getUser();
      
        $productRepo = $this->getDoctrine()->getRepository("ArtspaceBundle:Product");
        $product = $productRepo->find($id);
        
        $product->addUser($user);
        $user->addProduct($product);
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    
        
        $this->addFlash("success", "le produit a bien été ajouté !");
        return $this->redirect($this->generateUrl("panier"));
    }
    
    
    public function viewPanierAction(){
        $user = $this->getUser();
        $userlisteproduit = $user->getProducts();
        
        $params = array(
            "userlisteproduit" => $userlisteproduit
        );
        return $this->render("ArtspaceBundle:panier:panier.html.twig", $params);
    }
    
    
    public function deletePanierAction($id){
        
    $user = $this->getUser();    
    
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('ArtspaceBundle:Product')->find($id);

    $product->removeUser($user);
    $em->flush();

    return $this->redirect($this->generateUrl('panier'));
    } 

    
}
