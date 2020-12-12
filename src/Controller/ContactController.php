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
        $contact = new Contact();
        // ...

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $contact->setFirstName($form->get("firstName")->getData())
                ->setLastName($form->get("lastName")->getData())
                ->setMail($form->get("mail")->getData())
                ->setMessage($form->get("message")->getData())
                ->setDepartmentDestination($form->get("departmentDestination")->getData());

            $entityManager->persist($contact);
            $entityManager->flush();

            $mail->sendMail($contact);
            return $this->redirectToRoute('contact');

        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
