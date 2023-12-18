<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invitation:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['invitation:read'])]
    private ?Event $forEvent = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['invitation:read'])]
    private ?Profile $toProfile = null;

    #[ORM\Column(length: 255)]
    #[Groups(['invitation:read'])]
    private ?string $status = "pending";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForEvent(): ?Event
    {
        return $this->forEvent;
    }

    public function setForEvent(?Event $forEvent): static
    {
        $this->forEvent = $forEvent;

        return $this;
    }

    public function getToProfile(): ?Profile
    {
        return $this->toProfile;
    }

    public function setToProfile(?Profile $toProfile): static
    {
        $this->toProfile = $toProfile;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
