<?php

namespace EventBundle\Controller;

use Doctrine\DBAL\Types\DateType;
use EventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EventBundle\Entity\Event as MyCustomEvent;


/**
 * Event controller.
 *
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->findAll();
        $last_event_id= $em->getRepository('EventBundle:Event')->findLast();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        var_dump($last_event_id);
        return $this->render('EventBundle:event:index.html.twig', array(
            'events' => $pagination,
            'id' => $last_event_id[0]['id']
        ));
    }

    /**
     * Creates a new event entity.
     *
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $content =$request->getContent();
        $data = json_decode($content, true);



        if($data["ajax"]=="true")
        {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $startingDate = \DateTime::createFromFormat('Y-m-d', $data["startingDate"]);
            $endingDate = \DateTime::createFromFormat('Y-m-d', $data["endingDate"]);
            $nbPerson =$data["nbPerson"];
            $nbTable =$data["nbTable"];
            $band =$data["band"];
            $cost =$data["cost"];
            $title =$data["title"];
            $event->setTitle($title);
            $event->setStartDatetime($startingDate);
            $event->setEndDatetime($endingDate);
            $event->setNbPerson($nbPerson);
            $event->setNbTable($nbTable);
            $event->setBand($band);
            $event->setCost($cost);
            $event->setUrl("/admin/event/".$data["id"]."/show");
            $event->setBgColor("#ff5719");
            $event->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush($event);

        }


        return $this->render('EventBundle:event:new.html.twig', array(
            'event' => $event

        ));
    }

    /**
     * Finds and displays a event entity.
     *
     */
    public function showAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $users = $em->getRepository('UserBundle:User')->findAll();
        $participants = $em->getRepository('EventBundle:Participants')->findParticipant($event->getId());
        return $this->render('EventBundle:event:show.html.twig', array(
            'event' => $event,
            'users' => $users,
            'current_user' => $user,
            'participants' => $participants

        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     */
    public function editAction(Request $request, Event $event)
    {
        $editForm = $this->createForm('EventBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
        }

        return $this->render('EventBundle:event:edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     */
    public function deleteAction(Request $request, Event $event)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush($event);

        return $this->redirectToRoute('event_index');
    }



}
