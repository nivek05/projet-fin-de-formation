<?php 

namespace App\Utils;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class ServiceMailer
{
    private MailerInterface $mailer;
    
    //configuration-> service.yaml
    private $adminEmail;
    private $subjectEmail;
    private $subjectEmailReservation;
    private $subjectEmailProfileUpdateDelete;
    
   
    
    public function __construct(MailerInterface $mailer, string $adminEmail, string $subjectEmail, string $subjectEmailReservation, string $subjectEmailProfileUpdateDelete)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->subjectEmail = $subjectEmail;
        $this->subjectEmailReservation = $subjectEmailReservation;
        $this->subjectEmailProfileUpdateDelete = $subjectEmailProfileUpdateDelete;
    }
    
    /******************************************************************/
    /************** Send Mail for Contact             *****************/
    /******************************************************************/
    
    public function sendEmail($contact): bool
    {
        
        $email = (new TemplatedEmail())
           
            ->from($contact->get('email')->getData())
            ->to($this->adminEmail) // Mail perso pour test - A redefinir quand projet en ligne
            ->subject($this->subjectEmail)
            //Use of a mail template
            ->htmlTemplate('mail-template/mail.html.twig')
            ->context([
            'nom' => $contact->get('nom')->getData(),
            'mail' => $contact->get('email')->getData(),
            'message' => $contact->get('message')->getData(),
            'motif' => $contact->get('motif')->getData(),
            ]);

        // send the email
        $this->mailer->send($email);

        return true;
    }
    
    /******************************************************************/
    /************** Mail sending for the reservation *****************/
    /******************************************************************/
    
    public function sendEmailReservation($reservation, $quantity, $totalPrice, $plainPassword, $workshopName, $disponibilityDate): bool
    {
        
        $email = (new TemplatedEmail())
        
            ->from($this->adminEmail) // A modifier adminEmail -> pour test 
            ->to($this->adminEmail) // Mail perso pour test - A redefinir quand projet en ligne
            ->subject($this->subjectEmailReservation)
            //utilisation d'un template mailReservation
            ->htmlTemplate('mail-template/mailReservation.html.twig')
            ->context([
            'nom' => $reservation['name'],
            'firstName' => $reservation['firstName'],
            'mail' => $reservation['email'],
            'datePost' => $reservation['reservationDate']->format('d-m-Y H:i'),
            'meetingDate' => $disponibilityDate->format('d-m-Y H:i'),
            'meetingPlace' => $reservation['meetingPlace'],
            'totalPrice' => $totalPrice,
            'quantity' => $quantity,
            'password' =>$plainPassword,
            'workshop' => $workshopName
            
            ]);
            
        // send the email
        $this->mailer->send($email);

        return true;
    }
    
    /******************************************************************/
    /*** Send Mail for delete and modification ************************/
    /******************************************************************/
    
    public function sendEmailProfileUpdateDelete($message): bool
    {
        
        $email = (new TemplatedEmail())
        
            ->from($this->adminEmail) // A modifier adminEmail -> pour test 
            ->to($this->adminEmail) // Mail perso pour test - A redefinir quand projet en ligne
            ->subject($this->subjectEmailProfileUpdateDelete)
            //utilisation d'un template mailReservation
            ->htmlTemplate('mail-template/mailProfileUpdateDelete.html.twig')
            ->context([
            'message' => $message
            
            ]);
            
        // send the email
        $this->mailer->send($email);

        return true;
    }
    
    
    
}
