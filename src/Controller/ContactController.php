<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Manage\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param Mail $mail
     * @return Response
     */
    public function index(Request $request, Mail $mail): Response
    {
        //Instenciate object contact to send mail and save on DB
        $contact = new Contact();

        //Creating form to print
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        //check if the form is sent
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setFirstName($form->get("firstName")->getData())
                ->setLastName($form->get("lastName")->getData())
                ->setMail($form->get("mail")->getData())
                ->setMessage($form->get("message")->getData())
                ->setDepartmentDestination($form->get("departmentDestination")->getData());

            //Send mail /!\ you need to capture it with mailcatcher -Dev-
            $mail->sendMail($contact);

            //Save on DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            //Redirect on the same page -unique page for the moment-
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
