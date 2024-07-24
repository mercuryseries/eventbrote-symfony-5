<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Registration;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventRegistrationsController extends AbstractController
{
    /**
     * @Route("/events/{event}/registrations", name="events.registrations.index", methods={"GET"})
     */
    public function index(Event $event): Response
    {
        $registrations = $event->getRegistrations();

        return $this->render('events/registrations/index.html.twig', [
            'event' => $event,
            'registrations' => $event->getRegistrations(),
        ]);
    }

    /**
     * @Route("/events/{event}/registrations/create", name="events.registrations.create", methods={"GET", "POST"})
     */
    public function create(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        $registration = new Registration;

        $form = $this->createForm(RegistrationFormType::class, $registration);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registration->setEvent($event);

            $em->persist($registration);
            $em->flush();

            $this->addFlash('success', "Thanks, you're registered!");

            return $this->redirectToRoute('events.registrations.index', ['event' => $event->getId()]);
        }

        return $this->render('events/registrations/create.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}
