<?php


namespace App\Manage;


use App\Entity\Contact;
use App\Repository\DepartmentRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class Mail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(Contact $contact){
        try {
        $email = (new Email())

            ->from('no-reply@test.com')
            ->to('ozgurdenizsag@gmail.com',$contact->getDepartmentDestination()->getMailDepartment())
            ->subject('Envoie de test')
            ->text($contact->getDepartmentDestination()->getNameDepartment(). " " .
                $contact->getFirstName(). " " .
                $contact->getLastName(). " " .
                $contact->getMail(). " " .
                $contact->getMessage());

            $this->mailer->send($email);
            return ;
        } catch (TransportExceptionInterface $e) {
            return $e;
        }

    }

}