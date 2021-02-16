<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Utils\ServiceMailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

//For managing errors when sending an email
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    
    /**
    * @Route("/contact", name="contact")
    */
    public function contactForm(Request $request, MailerInterface $mailer, ServiceMailer $serviceMailer): Response{
        
        //Creating the form
        $form = $this->createForm(ContactType::class);
        //
        $contact = $form->handleRequest($request);
        
        //Si le formulaire a été submit et valide
        if( $form->isSubmitted() && $form->isValid() ){
            
            
            /***************************************************/
            /*      Storage in BDD of the message              */
            /*      of the message, name and email             */
            /*      of the contact tel, Reason and date        */
            /***************************************************/
            
            $em = $this->getDoctrine()->getManager();
            
            $addContact = new Contact();
            $addContact->setContactName($contact->get('nom')->getData());
            $addContact->setContactEmail($contact->get('email')->getData());
            $addContact->setContactMessage($contact->get('message')->getData());
            $addContact->setContactRgpd($contact->get('rgpd')->getData());
            $addContact->setContactTel($contact->get('telephone')->getData());
            $addContact->setContactReason($contact->get('motif')->getData());
            
            //get today's date
            $addContact->setContactDate(new \DateTime('now'));
            
            $em->persist($addContact);
            
            //flush the request
            $em->flush();
        
            /**********************************************/
            /*       Send Email, use Service -> src/utils */
            /**********************************************/
            
            try{
                if ($serviceMailer->sendEmail($contact)) {
                    $this->addFlash('message', 'votre email a bien été envoyé'); // Message stored on the session - Disappears once retrieved
                    //on redirige sur la meme page
                    return $this->redirectToRoute('contact');
                }
            }catch (TransportExceptionInterface $e) {
                
                $this->addFlash('message', 'Un problème est survenu : Essayer de renvoyer le formulaire');
                //on redirige sur la meme page
                return $this->redirectToRoute('contact');
            }
        }
       
        return $this->render('contactForm.html.twig', [
            'form'=>$form->createView(),
            
        ]);
    }
}