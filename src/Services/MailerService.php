<?php

namespace App\Services;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Envelope;
// use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;

class MailerService
{
    //public function send(RawMessage $message, Envelope $envelope = null): void;
    public function sendEmail(MailerInterface $mailer, $message, $receiver, $payment)
    {
       

    
        $email = (new Email())
            ->from('dawit120@gmail.com')
            ->to($receiver)
            //->to($receiver/*, $receiver2)
            //->cc('ferid.bedru@gmail.com','ferid2.bedru@gmail.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('LMS '.$payment)
            // ->text('My first for Payroll notification')
            ->html($message);

             

        try {
             
            // $mailer->send($email,"dawit120@gmail.com");
             $mailer->send($email);
           // $mailer->send($msg,$envelope);
        } catch (Exception $e) {
            dd($e);

            return 0; //new Response('<html><body><p>Connection Problem</b></body></html>');
//die;
        }




        return 1; //new Response('<html><body>Email sent successfully1</body></html>');
    }
}
