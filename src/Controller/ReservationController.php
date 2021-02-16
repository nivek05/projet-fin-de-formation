<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Workshop;
use App\Entity\Disponibility;

use App\Utils\ServiceMailer;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//For managing errors when sending an email
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;



class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/{id}", name="reservation")
     */
    public function index( SessionInterface $session, $id): Response
    {
        
        $workshop = $this->getDoctrine()->getRepository(Workshop::class)->find($id);
        if($workshop === null){
            return $this->redirectToRoute('coaching');
        }
        //Start a resa session
        
        $reservation = $session->get('resa', []);
       
        $reservation = ['id'=>$id,'step'=>1];
        
        $session->set('resa', $reservation);

        return $this->render('reservation/workshopPresentation.html.twig', [
            'id'=> $id,
            'workshop' => $workshop
        ]);
    }

    /**
     * @Route("/reservation-step", name="reservation_step")
     */
    public function reservationStepForm(Request $request, SessionInterface $session): Response{
        
        //if user connected
        if( $this->getUser() ){
            $user = $this->getUser();
            //Creating the form width informations user
            $formResa = $this->createForm(ReservationType::class, $user);
        }else{
            //form empty
            $formResa = $this->createForm(ReservationType::class);
        }
        
        //recover the resa session
        $reservation = $session->get('resa');
        
        $formResa->handleRequest($request);
       
            //If the form is submit and valid
            if( $formResa->isSubmitted() && $formResa->isValid() ){
                
                $name = $formResa->get('user_name')->getData();
                $firstName = $formResa->get('user_firstname')->getData();
                $email = $formResa->get('email')->getData();
                $tel = $formResa->get('user_tel')->getData();
                $quantity =$formResa->get('quantity')->getData();
                $meetingPlace = $formResa->get('meetingPlace')->getData();
                $reservationDate = new \DateTime('now');
                $meetingDate = $formResa->get('disponibility_date')->getData();
                
                //$data = $formResa->getData();
                
                //put step to the value of 2
                $step = 2;
                //add the form data to the session
                $reservation =
                [
                    'id'=>$reservation['id'],
                    'step'=>$step,
                    'name'=>$name,
                    'firstName'=> $firstName,
                    'email'=>$email,
                    'telephone'=> $tel,
                    'quantity'=> $quantity,
                    'meetingPlace'=>$meetingPlace,
                    'reservationDate'=>$reservationDate,
                    'meetingDate'=>$meetingDate
                
                ];
               
                $session->set('resa', $reservation);
                return $this->redirectToRoute('reservation-step2');
            }

        return $this->render('reservation/resaStepForm.html.twig', [
            'formResa'=>$formResa->createView(),
        ]);
    }
    
    /**
     * @Route("/reservation-step2", name="reservation_step2")
     */
    public function resaStep2Form(Request $request, SessionInterface $session, UserPasswordEncoderInterface $encoder, MailerInterface $mailer, ServiceMailer $serviceMailer): Response
    {   
        /***************************************************************************************/
        /********* ONLINE FALSE PAYMENT SIMULATION -> ENVIRONNEMENT                          ***/
        /********* CREATION OF A PAYMENT FORM            ***************************************/
        /***************************************************************************************/
     
        //recover the session 
        $reservation = $session->get('resa');
        //if session null
        if ($reservation === null) {
            //return to the resaStepForm form
            return $this->redirectToRoute('coaching');
        }
        
        $workshop = $this->getDoctrine()->getRepository(Workshop::class)->find($reservation['id']);
        //recover the title of the workshop -> for sending in the email
        $workshopName = $workshop->getWorkshopName();
       
        //get the number of people and we multiply to get the total price.
        $workshopPrice = $workshop->getWorkshopPrice();
        $quantity = $reservation['quantity'];
        $totalPrice = $workshopPrice * $quantity;
        
        
        //Creation of the credit card form -> to be modified by a real payment system
        $formCreditCard = $this->createFormBuilder()
           
            ->add('creditCardName', TextType::class, ['label'=>'Nom de la carte de credit','attr' => ['placeholder' => 'mettre la valeur nametest']])
            ->add('creditCardNumber',TextType::class,['label'=>'Numéro de la carte de credit','attr' => ['placeholder' => 'Mettre la valeur 1234']])
            ->add('dateExp', TextType::class,['attr' => ['label'=>"Date d'expiration",'placeholder' => 'mettre la valeur 02/2021']])
            ->add('codeCVV', TextType::class,['attr' => ['label'=>"Code CVV",'placeholder' => 'mettre la valeur 159']])
            ->add('Payer', SubmitType::class)
            ->getForm();
        
        $formCreditCard->handleRequest($request);
        
        //if request is valid and submit and use post method
        if ($request->isMethod('POST')) {
            
            if ($formCreditCard->isSubmitted() && $formCreditCard->isValid()) {
                
                $validFormCreditCard = $formCreditCard->getData();
                
                //False condition for checking bank card data
                //Not taken into account in the database for this example
                if(
                $validFormCreditCard['creditCardNumber'] ==="1234" 
                && $validFormCreditCard['creditCardName']==="nametest"
                && $validFormCreditCard['dateExp']==="02/2021"
                && $validFormCreditCard['codeCVV'] ==="159"
                ){
                    
                    $em = $this->getDoctrine()->getManager();
                    
                    /***************************************************/
                    /**** Adding USER information to the database  *****/
                    /***************************************************/
                
                    //Check the existence of the user's email in the database
                   
                    $findUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$reservation['email']]);
                    
                    // if the user email is not null in the database
                    // if($findUser != null){
                    //     $user = $this->getDoctrine()->getRepository(User::class)->find($findUser->getId());
                    // }
                    
                    //If no user corresponding to the email in the database and if the user is not connected, a new user is added
                    if($findUser === null){
                        
                        $addUser = new User();

                        //Creation of the random password
                        $bytes = random_bytes(5); //Generates an arbitrary length string of cryptographic random bytes
                        
                        $plainPassword = bin2hex($bytes); //Converts binary data to hexadecimal representation
                        
                        //Encode the password
                        $encoded = $encoder->encodePassword($addUser, $plainPassword);
                       
                        $addUser->setEmail($reservation['email']);
                        $addUser->setRoles(['ROLE_USER']);
                        $addUser->setPassword($encoded);
                        $addUser->setUserName($reservation['name']);
                        $addUser->setUserFirstname($reservation['firstName']);
                        $addUser->setUserTel($reservation['telephone']);
                      
                    }else{
                        //Do not return the password for the already existing user
                        $plainPassword = 'Vous avez déjà avez un compte';
                        //the user email is not null in the database
                        $user = $this->getDoctrine()->getRepository(User::class)->find($findUser->getId());
                    }
                    
                    /* Check date disponibility */
                    //$disponibilityDate = $reservation['meetingDate']->getDisponibilityDate();
                    $disponibility = $this->getDoctrine()->getRepository(Disponibility::class)->findOneBy(['id'=>$reservation['meetingDate']]);
                    $disponibilityDate = $disponibility->getDisponibilityDate();
                   
                    /*******************************************************/
                    /* Adding RESERVATION information in the database      */
                    /*******************************************************/
                    
                    $addReservation = new Reservation();
                    $addReservation->setReservationInfoRdv($reservation['meetingPlace']);
                    $addReservation->setReservationDate($reservation['reservationDate']);
                    $addReservation->setReservationPersQuantity($quantity);
                    $addReservation->setReservationTotalPrice($totalPrice);
                    $addReservation->setWorkshop($workshop);
                    $addReservation->setDisponibility($disponibility);
                    
                    //If user in the database is null
                    if($findUser === null){
                        //add the new user
                        $addReservation->setUser($addUser);
                        $em->persist($addUser);
                    }else{
                        // else add the existing user to the reservation
                        $addReservation->setUser($user);
                        $em->persist($user);
                    }
                
                    /*******************************************************/
                    /* Update DISPONIBILITY information in the database    */
                    /*******************************************************/
                    $disponibility->setDisponibilityIsDispo(0);
                    
                   
                    /* Persist */
                    $em->persist($addReservation);
                    //execute the request
                    $em->flush();
                    
                    /********************************************************/
                    /* SEND MAIL                                            */
                    /********************************************************/
                    try{
                        var_dump($disponibilityDate);
                        $serviceMailer->sendEmailReservation($reservation, $quantity, $totalPrice, $plainPassword, $workshopName, $disponibilityDate);
                        
                    }catch (TransportExceptionInterface $e) {
                        $this->addFlash('message', 'Un problème est survenu : Essayer de renvoyer le formulaire');
                        //on redirige sur la meme page
                        return $this->redirectToRoute('reservation-step2');
                    }
                    
                    //redirection to the validation page
                    return $this->redirectToRoute('reservation-stepValidation');
                }
                else{
                    
                    $this->addFlash('message', 'Votre nom et votre numero de carte de corresponde pas'); //Message stored on session - Disappears when retrieved
                    //redirection step 2
                    return $this->redirectToRoute('reservation-step2');
                }
            }
        }
        
        return $this->render('reservation/resaStep2Form.html.twig', [
            'formCreditCard'=>$formCreditCard->createView(),
            'totalPrice' => $totalPrice,
            'quantity' => $quantity,
            'resaId' => $reservation['id']
            
        ]);
    }
    
    /**
     * @Route("/reservation-stepValidation", name="reservation_stepValidation")
     */
    public function resaStepValidation(SessionInterface $session): Response
    {   
        //recover the session 
        $reservation = $session->get('resa');
        //if session null
        if ($reservation === null) {
            //return to the resaStepForm form
            return $this->redirectToRoute('home');
        }
        //clear session
        $session->clear();
        
        //Validation reservation page
        return $this->render('reservation/resaStepValidation.html.twig', [
            'controller_name' => 'ReservationController',
           
            
        ]);
    }

}