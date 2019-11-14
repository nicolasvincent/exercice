<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ContactType;
use App\Service\ServiceMail;
use App\Entity\Departement;
use App\Entity\Contact;
/**
 * @Route("/contact", name="api_")
 */
class ContactController extends FOSRestController
{

    /**
     * @Rest\Get("/departement")
     *
     * @return Reponse
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Departement::class);
        $depart = $repository->findall();
        return $this->handleView($this->view($depart, Response::HTTP_OK));
    }

    /**
     *
     * @Rest\Post("/save")
     *
     * @return Response
     */
    public function postAction(Request $request,ServiceMail $mail)
    {
        $client = new Contact();
        $form = $this->createForm(ContactType::class, $client);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $dataDepartement = $this->getDoctrine()->getRepository(Departement::class);
            $data2 = $dataDepartement->findOneById($client->getChoixDepartement());
            $mail->SendMail($data2,$client);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            return $this->handleView($this->view( ["Status:"=> "Enregistrer"],Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}
