<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Manage\Mail;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"POST"})
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

            //dd($request->getContent());
            //Get datas
            $contact = $form->getData();

            //Send mail /!\ you need to capture it with mailcatcher -Dev-
            $mail->sendMail($contact);

            //Save on DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            //Some message to notify the user
            $this->addFlash('success', 'Envoyé avec succès');

            return $this->json(
                [
                    "success" => true,
                    "id" => $contact->getId()
                ],
                Response::HTTP_CREATED
            );


            //Redirect on the same page -unique page for the moment-
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contacts/list", name="contactsList", methods={"GET"})
     */
    public function getContactsList(ContactRepository $repository, SerializerInterface $serializer) : JsonResponse
    {

        $contactsList = $repository->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($contactsList, 'json',
            [AbstractNormalizer::ATTRIBUTES => ['id','firstName','lastName','mail', 'message', 'departmentDestination' => ['id','nameDepartment','mailDepartment']]]);
        return $this->json(
            $data,
            200,
            []
        );
    }

    /**
     * @Route("/contacts/{id}", name="contactId")
     */
    public function getContact(ContactRepository $repository, int $id, SerializerInterface $serializer)
    {
        $fichecontact = $repository->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($fichecontact, 'json',
            [AbstractNormalizer::ATTRIBUTES => ['id','firstName','lastName','mail', 'message', 'departmentDestination' => ['id','nameDepartment','mailDepartment']]]);
        return $this->json(
            $data,
            200,
            []
        );
    }

}
