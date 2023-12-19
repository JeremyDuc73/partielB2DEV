<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Entity\Event;
use App\Entity\Suggestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ContributionController extends AbstractController
{
    #[Route('/event/{id}/contribution/add', methods: ['POST'])]
    public function addContribution(Event $event, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($this->getUser()->getProfile() == $event->getOrganizer() or $event->getParticipants()->contains($this->getUser()->getProfile()))
            {
                $contribution = $serializer->deserialize($request->getContent(), Contribution::class, 'json');
                $contribution->setOfEvent($event);
                $contribution->setOfProfile($this->getUser()->getProfile());
                $manager->persist($contribution);
                $manager->flush();
                return $this->json($contribution, 201, [], ["groups"=>'contribution:readSimple']);
            }
            return $this->json("You are not a participant or the organizer of this event", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }

    #[Route('/event/{eventId}/suggestion/{suggestionId}/addtocontribution', methods: ['POST'])]
    public function addContributionFromSuggestion(
        #[MapEntity(mapping: ['eventId'=>'id'])] Event $event,
        #[MapEntity(mapping: ['suggestionId'=>'id'])] Suggestion $suggestion,
        EntityManagerInterface $manager
    )
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($this->getUser()->getProfile() == $event->getOrganizer() or $event->getParticipants()->contains($this->getUser()->getProfile()))
            {

                $contribution = new Contribution();
                $contribution->setOfEvent($event);
                $contribution->setOfProfile($this->getUser()->getProfile());
                $contribution->setProduct($suggestion->getProduct());
                $contribution->setFromSuggestion($suggestion);
                $manager->persist($contribution);
                $manager->flush();
                $suggestion->setForContribution($contribution);
                $suggestion->setForProfile($this->getUser()->getProfile());
                $suggestion->setIsTaken(true);
                $manager->persist($suggestion);
                $manager->flush();
                return $this->json($contribution, 201, [], ["groups"=>'contribution:readAll']);
            }
            return $this->json("You are not a participant or the organizer of this event", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }

    #[Route('/event/{id}/contributions', methods: ['GET'])]
    public function getAllContributions(Event $event)
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($event->getParticipants()->contains($this->getUser()->getProfile()) or $event->getOrganizer() == $this->getUser()->getProfile())
            {
                $contributions = $event->getContributions();
                return $this->json($contributions, 200, [], ["groups"=>"contribution:readAll"]);
            }
            return $this->json("You are not a participant or the organizer of this event", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }
    #[Route('/event/{eventId}/contribution/{contributionId}/edit', methods: ['PUT'])]
    public function editContribution(
        #[MapEntity(mapping: ['eventId'=>'id'])] Event $event,
        #[MapEntity(mapping: ['contributionId'=>'id'])] Contribution $contribution,
        SerializerInterface $serializer, EntityManagerInterface $manager, Request $request
    )
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($contribution->getOfProfile() == $this->getUser()->getProfile())
            {
                $newContribution = $serializer->deserialize($request->getContent(), Contribution::class, 'json', ["object_to_populate"=>$contribution]);
                $manager->persist($newContribution);
                $manager->flush();
                return $this->json($newContribution, 201, [], ["groups"=>'contribution:readAll']);
            }
            return $this->json("This is not your contribution", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }

    #[Route('/event/{eventId}/contribution/{contributionId}/remove', methods: ['DELETE'])]
    public function removeContributionAsParticipant(
        #[MapEntity(mapping: ['eventId'=>'id'])] Event $event,
        #[MapEntity(mapping: ['contributionId'=>'id'])] Contribution $contribution,
        EntityManagerInterface $manager
    )
    {
        if ($event->isPrivate() && $event->isPrivatePLace())
        {
            if ($contribution->getOfProfile() == $this->getUser()->getProfile())
            {
                if ($contribution->getFromSuggestion() !== null)
                {
                    $suggestion = $contribution->getFromSuggestion();
                    $suggestion->setForContribution(null);
                    $suggestion->setForProfile(null);
                    $suggestion->setIsTaken(false);
                    $manager->persist($suggestion);
                    $manager->flush();
                }
                $manager->remove($contribution);
                $manager->flush();
                return $this->json("contribution delete", 201);
            }
            elseif ($this->getUser()->getProfile() == $event->getOrganizer() && $contribution->getFromSuggestion() == null)
            {
                $manager->remove($contribution);
                $manager->flush();
                return $this->json("contribution delete", 201);
            }
            return $this->json("This is not your contribution", 401);
        }
        return $this->json("This event is not private or/and not in private place", 401);
    }
}
