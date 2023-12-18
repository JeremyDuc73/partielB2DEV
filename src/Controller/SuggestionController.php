<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Suggestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class SuggestionController extends AbstractController
{
    #[Route('/event/{id}/suggestion/add', methods: ['POST'])]
    public function addSuggestion(Event $event, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        if ($event->isPrivate() && $event->isPrivatePLace() && $this->getUser()->getProfile() == $event->getOrganizer())
        {
            $suggestion = $serializer->deserialize($request->getContent(), Suggestion::class, 'json');
            $suggestion->setOfEvent($event);
            $manager->persist($suggestion);
            $manager->flush();
            return $this->json($suggestion, 201, [], ["groups"=>'suggestion:read']);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }

    #[Route('/event/{id}/suggestions', methods: ['GET'])]
    public function getSuggestionsByEvent(Event $event)
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($event->getParticipants()->contains($this->getUser()->getProfile()) or $event->getOrganizer() == $this->getUser()->getProfile())
            {
                $suggestions = $event->getSuggestions();
                return $this->json($suggestions, 200, [], ["groups"=>"suggestion:read"]);
            }
            return $this->json("You are not a participant or the organizer of this event", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }
}
