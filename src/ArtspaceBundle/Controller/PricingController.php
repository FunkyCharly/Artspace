<?php

namespace ArtspaceBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        return $this->render('ArtspaceBundle:admin:backoffice.html.twig');
    }
}
