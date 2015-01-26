<?php

namespace ArtspaceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{
    /**
     * @Route("/panier/add/{productId}", name="addToPanier", requirements={"productid":"\d+"})
     */
    public function addToPanierAction()
    {
        $user = $this->getUser();
        
        $productRepo = $this->getDoctrine()->getRepository("ArtspaceBundle:Product");
        $product = $productRepo->find();
        
        $product->addUser($user);
        $user->addProduct($product);
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    
        //redirige vers productDetails avec un message
        $this->addFlash("success", "le produit a bien été ajouté !");
        return $this->redirect($this->generateUrl("viewPanier"));
    }
    
    /**
     * @Route("/panier", name="viewPanier")
     */
    public function viewPanierAction(){
        $user = $this->getUser();
        $userlisteproduit = $user->getProducts();
        
        $params = array(
            "userlisteproduit" => $userlisteproduit
        );
        return $this->render("panier/panier.html.twig", $params);
    }
}
