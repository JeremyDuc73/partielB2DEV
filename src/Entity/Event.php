<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:readOne', 'invitation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:readOne', 'invitation:read'])]
    private ?string $place = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:readOne', 'invitation:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['event:readOne'])]
    private ?\DateTimeInterface $startingDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['event:readOne'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'organizedEvents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:readOne'])]
    private ?Profile $organizer = null;

    #[ORM\Column]
    #[Groups(['event:readOne'])]
    private ?bool $private = null;

    #[ORM\Column]
    #[Groups(['event:readOne'])]
    private ?bool $privatePlace = null;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'eventsParticipant')]
    #[Groups(['event:readOne', 'participant:read'])]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'forEvent', targetEntity: Invitation::class)]
    private Collection $invitations;

    #[ORM\Column]
    private ?bool $onSchedule = true;

    #[ORM\OneToMany(mappedBy: 'ofEvent', targetEntity: Contribution::class)]
    private Collection $contributions;

    #[ORM\OneToMany(mappedBy: 'ofEvent', targetEntity: Suggestion::class)]
    private Collection $suggestions;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOrganizer(): ?Profile
    {
        return $this->organizer;
    }

    public function setOrganizer(?Profile $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function isPrivatePLace(): ?bool
    {
        return $this->privatePlace;
    }

    public function setPrivatePlace(bool $privatePlace): static
    {
        $this->privatePlace = $privatePlace;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Profile $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Profile $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setForEvent($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getForEvent() === $this) {
                $invitation->setForEvent(null);
            }
        }

        return $this;
    }

    public function isOnSchedule(): ?bool
    {
        return $this->onSchedule;
    }

    public function setOnSchedule(bool $onSchedule): static
    {
        $this->onSchedule = $onSchedule;

        return $this;
    }

    /**
     * @return Collection<int, Contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Contribution $contribution): static
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setOfEvent($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getOfEvent() === $this) {
                $contribution->setOfEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suggestion>
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setOfEvent($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getOfEvent() === $this) {
                $suggestion->setOfEvent(null);
            }
        }

        return $this;
    }
}
