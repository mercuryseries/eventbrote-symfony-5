<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @Route("/events", name="events.index", methods={"GET"})
     */
    public function index(EventRepository $repo): Response
    {
        $events = $repo->findUpcoming(); 

        return $this->render('events/index.html.twig', compact('events'));
    }
    
    /**
     * @Route("/events/create", name="events.create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event;

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'Event successfully created!');

            return $this->redirectToRoute('events.show', ['id' => $event->getId()]);
        }

        return $this->render('events/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/events/{id<[0-9]+>}", name="events.show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('events/show.html.twig', compact('event'));
    }

    /**
     * @Route("/events/{id<[0-9]+>}/edit", name="events.edit", methods={"GET", "PATCH"})
     */
    public function edit(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EventFormType::class, $event, ['method' => 'PATCH']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Event successfully updated!');

            return $this->redirectToRoute('events.show', ['id' => $event->getId()]);
        }

        return $this->render('events/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/{id<[0-9]+>}", name="events.delete", methods={"DELETE"})
     */
    public function delete(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('events.delete', $request->request->get('_token'))) {
            $em->remove($event);
            $em->flush();

            $this->addFlash('danger', 'Event successfully deleted!');
        }

        return $this->redirectToRoute('events.index');
    }
}
