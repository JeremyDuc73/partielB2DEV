<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Entity\Profile;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class EventController extends AbstractController
{
    #[Route('/events', methods: ['GET'])]
    public function getPublicEvents(EventRepository $repository)
    {
        $publicEvents = [];
        $events = $repository->findAll();
        foreach ($events as $event){
            if (!$event->isPrivate()){
                $publicEvents [] = $event;
            }
        }
        return $this->json($publicEvents, 200, [], ["groups"=>"event:readOne"]);
    }
    #[Route('/myorganizedevents', methods: ['GET'])]
    public function getMyOrganizedEvents(EventRepository $repository)
    {
        $myEvents = [];
        $events = $repository->findAll();
        foreach ($events as $event)
        {
            if ($event->getOrganizer() == $this->getUser()->getProfile())
            {
                $myEvents [] = $event;
            }
        }
        return $this->json($myEvents, 200, [], ["groups"=>"event:readOne"]);
    }
    #[Route('/myevents', methods: ['GET'])]
    public function getMyEvents(EventRepository $repository)
    {
        $myEvents = [];
        $events = $repository->findAll();
        foreach ($events as $event)
        {
            foreach ($event->getParticipants() as $participant){
                if ($participant == $this->getUser()->getProfile())
                {
                    $myEvents [] = $event;
                }
            }
        }

        return $this->json($myEvents, 200, [], ["groups"=>"event:readOne"]);
    }


    #[Route('/event/create', methods: ['POST'])]
    public function create(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        $currentDate = new \DateTime();
        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');
        if ($event->getStartingDate() > $event->getEndDate()){
            return $this->json("Your end date is lower than the starting one", 401);
        }elseif ($event->getStartingDate() < $currentDate){
            return $this->json("Your event must start in the future", 401);
        }
        $event->setOrganizer($this->getUser()->getProfile());
        $manager->persist($event);
        $manager->flush();
        return $this->json($event, 201, [], ["groups"=>"event:readOne"]);
    }

    #[Route('/event/{id}/edit', methods: ['POST'])]
    public function editDates(Event $event, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request)
    {
        $currentDate = new \DateTime();
        $newEvent = $serializer->deserialize($request->getContent(), Event::class, 'json', ["object_to_populate"=>$event]);
        if ($newEvent->getStartingDate() > $newEvent->getEndDate()){
            return $this->json("Your end date is lower than the starting one", 401);
        }elseif ($newEvent->getStartingDate() < $currentDate){
            return $this->json("Your event must start in the future", 401);
        }
        $manager->persist($newEvent);
        $manager->flush();
        return $this->json("dates modified", 201);
    }


    #[Route('/event/{id}/join', methods: ['POST'])]
    public function addParticipant(Event $event, EntityManagerInterface $manager)
    {
        if ($event->isPrivate()){
            return $this->json("you need to be invited to this event you cannot join it publicly", 401);
        }elseif ($event->getOrganizer() == $this->getUser()->getProfile()){
            return $this->json("This is your event", 401);
        }
        foreach ($event->getParticipants() as $participant)
        {
            if ($participant == $this->getUser()->getProfile())
            {
                return $this->json("you are already registered for this event", 401);
            }
        }
        $event->addParticipant($this->getUser()->getProfile());
        $manager->persist($event);
        $manager->flush();
        return $this->json($event, 201, [], ["groups"=>"event:readOne"]);
    }

    #[Route('/event/{id}/participants', methods: ['GET'])]
    public function getParticipants(Event $event)
    {
        $participants = $event->getParticipants();

        if ($event->isPrivate())
        {
            foreach ($event->getInvitations() as $invitation)
            {
                if ($this->getUser()->getProfile()->getInvitations()->contains($invitation))
                {
                    return $this->json($participants, 200, [], ["groups"=>"participant:read"]);
                }
            }
            if ($this->getUser()->getProfile() == $event->getOrganizer() or $event->getParticipants()->contains($this->getUser()->getProfile()))
            {
                return $this->json($participants, 200, [], ["groups"=>"participant:read"]);
            }
            else
            {
                return $this->json("You are not the organizer, a participant or invited to this event", 401);
            }
        }
        return $this->json($participants, 200, [], ["groups"=>"participant:read"]);
    }

    #[Route('/event/{id}/changeSchedule', methods: ['POST'])]
    public function changeSchedule(Event $event, EntityManagerInterface $manager)
    {
        if ($event->getOrganizer() !== $this->getUser()->getProfile())
        {
            return $this->json("You are not the organizer of this event", 401);
        }
        if ($event->isOnSchedule())
        {
            $event->setOnSchedule(false);
        }else{
            $event->setOnSchedule(true);
        }
        $manager->persist($event);
        $manager->flush();
        return $this->json("schedule changed", 201);
    }



}
