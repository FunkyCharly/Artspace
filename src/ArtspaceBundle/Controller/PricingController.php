<?php

namespace ArtspaceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ArtspaceBundle\Entity\Product;
use ArtspaceBundle\Form\ProductType;


class PricingController extends Controller
{
  
  public function ShowCategorieAction($categorie)
  {
      $repository = $this->getDoctrine()
    ->getManager()
    ->getRepository('ArtspaceBundle:Product');
    
    $listeproduit = $repository->findBycategorie($categorie);
    $params = array('listeproduit'=>$listeproduit,
                    'categorie'=>$categorie);
  
    return $this->render('ArtspaceBundle:Pricing_views:pricing.html.twig', $params);
    
  }
  
  public function ShowDetailAction($id)
  {
     
      
     
        //récupère le film depuis la bdd, en fonction de son id (présent dans l'URL)
        $repository = $this->getDoctrine()->getRepository("ArtspaceBundle:Product");
        $detail = $repository->find($id);

        $params = array(
            "detail" => $detail
            );

        //envoie la vue, en lui passant les variables
        return $this->render('ArtspaceBundle:Pricing_views:detail.html.twig', $params);    
   }
        
   

    public function ShowArtspaceAction()
    {
        return $this->render('ArtspaceBundle:Pricing_views:artspace.html.twig');
    }
    
    public function deleteProductAction($id){
        
       
    
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('ArtspaceBundle:Product')->find($id);

    $em->remove($product);
    $em->flush();

    return $this->redirect($this->generateUrl('artspace_categories'));
    }
    
    public function showAdminAction(){
        
        $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ArtspaceBundle:Product');
            $products = $repository->findAll();
            
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ArtspaceBundle:Commande');
            $commande = $repository->findAll();
            
            $params = array(
                'products'=> $products,
                'commande'=> $commande
            );
            return $this->render('ArtspaceBundle:admin:backoffice.html.twig', $params);
            }
    
    /**
     * Creates a new Product entity.
     *
     * 
     * @Method("POST")
     * @Template("ArtspaceBundle:admin:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Product();
        $form = $this->createForm(new ProductType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backoffice_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

}
