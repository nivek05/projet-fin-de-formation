<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        
         // If ADMIN authenticated redirect to the dashboard route
         if ($this->getUser() && $hasAccess) {
             
            return $this->redirectToRoute('dashboard');

         }
         // If User authenticated redirect to the profil user
         if($this->getUser()){
             
             return $this->redirectToRoute('profile-user');
         }
         

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
    * @Route("/logout", name="app_logout")
    */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
