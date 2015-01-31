<?php

namespace ArtspaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    public function ajoutPanierAction(Request $request)
    {
        $pid = $request->query->get('produit_id');
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('ArtspaceBundle:Product')->find($pid);
        $session = new Session();
        
        if ($session->has('panier'))
        {
            $panier = $session->get('panier');
            array_push($panier, $product);
        }
        else
        {
            $panier = Array('0'=>  $product);           
        }
        
        $session->set('panier', $panier);
       

        
        return $this->redirect($this->generateUrl('view_cart'));    
    }
        
    public function supprPanierAction(Request $request)
    {
        $opn = $request->query->get('opn');
        $session = new Session();
        
        if ($session->has('panier'))
        {
            $panier = $session->get('panier');
            $tab1 = array_slice($panier, 0, $opn);
            $tab2 = array_slice($panier, ($opn+1));
            $panier = array_merge($tab1, $tab2);            
        }
        $session->set('panier', $panier);
        $session_id = $session->getId();
        
        return $this->redirect($this->generateUrl('view_cart')); 
    }
    public function viewCartAction()
   {
    $session = new Session();
    $products = $session->get('panier');
    
     $params = array(
          "products" => $products
       );
      
     return $this->render("ArtspaceBundle:cart:cart.html.twig", $params);
   }
   
   public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ArtspaceBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backoffice_show'));
    }
    
}

