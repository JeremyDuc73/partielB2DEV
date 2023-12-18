<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Entity\Profile;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class InvitationController extends AbstractController
{
    #[Route('/event/{eventId}/invite/{profileId}')]
    public function sendInvitation(
        #[MapEntity(mapping: ['eventId'=>'id'])] Event $event,
        #[MapEntity(mapping: ['profileId'=>'id'])] Profile $profile,
        EntityManagerInterface $manager)
    {
        if (!$event->isPrivate())
        {
            return $this->json("Your event must be private to send invitations", 401);
        }elseif ($event->getOrganizer() !== $this->getUser()->getProfile())
        {
            return $this->json("You must be the organizer to send invitations");
        }elseif ($profile == $this->getUser()->getProfile())
        {
            return $this->json("You cannot invite yourself", 401);
        }elseif (!$event->isOnSchedule())
        {
            return $this->json("This event is cancelled", 401);
        }
        foreach ($profile->getInvitations() as $invitation){
            if ($invitation->getForEvent() === $event){
                return $this->json("this person is already invited", 401);
            }
        }

        $invitation = new Invitation();
        $invitation->setToProfile($profile);
        $invitation->setForEvent($event);
        $manager->persist($invitation);
        $manager->flush();
        return $this->json($invitation, 201, [], ["groups"=>"invitation:read"]);
    }
    #[Route('/myinvitations', methods: ['GET'])]
    public function myInvitations(InvitationRepository $repository)
    {
        $myInvitations = [];
        $invitations = $repository->findAll();
        foreach ($invitations as $invitation)
        {
            if ($invitation->getToProfile() == $this->getUser()->getProfile())
            {
                $myInvitations [] = $invitation;
            }
        }
        return $this->json($myInvitations, 200, [], ["groups"=>"invitation:read"]);
    }

    #[Route('/invitation/{id}/accept', methods: ['POST'])]
    public function accept(Invitation $invitation, EntityManagerInterface $manager)
    {
        $currentDate = new \DateTime();
        if ($invitation->getToProfile() !== $this->getUser()->getProfile())
        {
            return $this->json("This is not for you", 401);
        }
        elseif (!$invitation->getForEvent()->isOnSchedule())
        {
            return $this->json("This event is cancelled", 401);
        }
        elseif ($invitation->getForEvent()->getStartingDate() < $currentDate )
        {
            $invitation->setStatus("denied");
            $manager->persist($invitation);
            $manager->flush();
            return $this->json("This event has expired", 201);
        }
        $invitation->setStatus("accepted");
        $manager->persist($invitation);
        $manager->flush();
        $event = $invitation->getForEvent();
        $event->addParticipant($this->getUser()->getProfile());
        $manager->persist($event);
        $manager->flush();
        return $this->json("You joined this event", 201);
    }
}
