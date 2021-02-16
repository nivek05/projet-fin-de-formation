<?php

namespace App\Controller\UserProfile;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\UserType;
use App\Utils\ServiceMailer;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile-user", name="profile_user")
     */
    public function index(): Response
    {
        
        //recover the user with the token
        $userProfile = $this->get('security.token_storage')->getToken()->getUser();
       
        if(!$userProfile){
            throw new AccessDeniedException('Accès non autorisé !');
        }
        $userProfileId = $userProfile->getId();
        
        //get the reservations of the user if existing
        $userReservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['user' => $userProfileId] );
        
        return $this->render('user-profile/userProfile.html.twig', [
           'userProfile' => $userProfile,
           'userReservations' => $userReservations,
          
        ]);
    }
    
    /**
     * @Route("/profile-user-delete", name="profile_user_delete", methods={"POST"})
     */
     
    public function deleteUserProfile(Request $request, MailerInterface $mailer, ServiceMailer $serviceMailer): Response
    {
        $user = $this->getUser();
        if(!$user){
            throw new AccessDeniedException('Accès non autorisé !');
        }
        
        $userId = $request->request->get('user_id');
        
        if ( intval($userId) !== $user->getId() ) {
            
            throw new AccessDeniedException('Accès non autorisé !');
        }
    
        // Delete the id of the user in reservation and delete the user
        $em = $this->getDoctrine()->getManager();
        
        //get the reservations of the user
        $userReservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['user'=>$user]);

        //remove user from reservations
        for($i=0; $i< count($userReservations); $i++ ){
          
            $user->removeReservation($userReservations[$i]);
        }
        //Delete user
        $em->remove($user);
        $em->flush();
        
        try{
            $message = 'Votre compte a été supprimé';
            $serviceMailer->sendEmailProfileUpdateDelete($message);
            
        }catch (TransportExceptionInterface $e) {
            $this->addFlash('message', 'Un problème est survenu');
           
        }

        return $this->render('user-profile/userProfileMessage.html.twig', [
           
        ]);
    }
    
    
    /**
    * @Route("/profile-user-update", name="profile_user_update", methods={"GET, POST"})
    */
    public function updateUserProfile(UserPasswordEncoderInterface $encoder, Request $request, MailerInterface $mailer, ServiceMailer $serviceMailer): Response
    {
        
        $userProfile = $this->get('security.token_storage')->getToken()->getUser();
      
        //Creating the form
        $formUserUpdate = $this->createForm(UserType::class,$userProfile);
       
        $formUserUpdate->handleRequest($request);
        
        if ($formUserUpdate->isSubmitted() && $formUserUpdate->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $user = $em->getRepository(User::class)->find($userProfile->getId());
            if (!$user) {
                throw $this->createNotFoundException(
                    "Pas de user trouvé avec l'id".$user
                );
            }
            
            //check if the user's email does not exist in the database
            $findEmail = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$formUserUpdate->get('email')->getData()]);
            
            //if mail sent in the form is found in the database and it is different from the ProfileUser, a message is displayed
            if ( $findEmail != null && $userProfile->getEmail() != $formUserUpdate->get('email')->getData() ){
                var_dump('dans le if');
                $this->addFlash('message', "L'email est déjà existant dans la base");
                return $this->redirectToRoute('profile-update');
            }
            //Update in the profile database
            $user->setUserName($formUserUpdate->get('user_name')->getData());
            $user->setUserFirstname( $formUserUpdate->get('user_firstname')->getData());
            $user->setEmail($formUserUpdate->get('email')->getData());
            $user->setUserTel($formUserUpdate->get('user_tel')->getData());
            
            //if password not null, encode new password
            if( $formUserUpdate->get('pwd')->getData() !== null ){
                $plainPassword = $formUserUpdate->get('pwd')->getData();
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
            }
            
            $em->flush();
            
            $this->addFlash('message', 'Vos informations ont été modifiées'); //Message stored on session - Disappears when retrieved
            
            try{
            $message = 'Votre compte a été modifié';
            $serviceMailer->sendEmailProfileUpdateDelete($message);
            
            }catch (TransportExceptionInterface $e) {
                $this->addFlash('message', "Un problème pour l'envoi du mail est survenu");
               
            }
        }
        
        return $this->render('user-profile/userProfileUpdate.html.twig', [
           'userProfile' => $userProfile,
           'formUserUpdate' => $formUserUpdate->createView()
        ]);
    }
   
}