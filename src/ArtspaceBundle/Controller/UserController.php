<?php

namespace ArtspaceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

use ArtspaceBundle\Entity\User;
use ArtspaceBundle\Form\RegistrationType;
use ArtspaceBundle\Form\LostPasswordType;
use ArtspaceBundle\Form\ChangePasswordType;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserController extends Controller
{


    public function registerAction(Request $request)
    {        
        //les utilisateurs connectés sont redirigés vers l'accueil
        if ($this->getUser()){
            return $this->redirect( $this->generateUrl("register") );
        }

        //on crée un utilisateur vide (une instance de notre entité User)
        $user = new User();

        //on récupère une instance de notre formulaire
        //ce form est associé à l'utilisateur vide
        $registrationForm = $this->createForm(new RegistrationType(), $user);


        //traite le formulaire
        $registrationForm->handleRequest( $request );

        //si les données sont valides....
        if ( $registrationForm->isValid() ){
            //hydrate les autres propriétés de notre User

            //générer un salt
            $salt = $this->get('string_helper')->randomString(50);
            $user->setSalt( $salt );

            //générer un token
            $token = $this->get('string_helper')->randomString(30);
            $user->setToken( $token );

            //hacher le mot de passe
            //sha512, 5000 fois
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword( $user, $user->getPassword() );
            $user->setPassword( $encoded );

            //date d'inscription et date de modification
            $user->setDateRegistered( new \DateTime() );
            $user->setDateModified( new \DateTime() );

            //assigne toujours ce rôle aux utilisateurs du front-office
            $user->setRoles( array("ROLE_USER") );

            //sauvegarde le User en bdd
            $em = $this->get("doctrine")->getManager();
            $em->persist( $user );
            $em->flush();

        }

        //on shoot le formulaire à twig (on n'oublie pas le createView !)
        $params = array(
            "registrationForm" => $registrationForm->createView()
        );

        return $this->render('ArtspaceBundle:user:register.html.twig', $params);
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {

        //les utilisateurs connectés sont redirigés vers l'accueil
        if ( $this->getUser() ){
            return $this->redirect( $this->generateUrl("ShowCategorie") );
        }

        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

        return $this->render(
            'ArtspaceBundle:user:login.html.twig',
             array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
    
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        
    }
}
