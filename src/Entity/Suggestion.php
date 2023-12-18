<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['suggestion:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['suggestion:read'])]
    private ?bool $isTaken = false;

    #[ORM\Column(length: 255)]
    #[Groups(['suggestion:read'])]
    private ?string $product = null;

    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    private ?Profile $forProfile = null;

    #[ORM\ManyToOne(inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $ofEvent = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Contribution $ofContribution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsTaken(): ?bool
    {
        return $this->isTaken;
    }

    public function setIsTaken(bool $isTaken): static
    {
        $this->isTaken = $isTaken;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getForProfile(): ?Profile
    {
        return $this->forProfile;
    }

    public function setForProfile(?Profile $forProfile): static
    {
        $this->forProfile = $forProfile;

        return $this;
    }

    public function getOfEvent(): ?Event
    {
        return $this->ofEvent;
    }

    public function setOfEvent(?Event $ofEvent): static
    {
        $this->ofEvent = $ofEvent;

        return $this;
    }

    public function getOfContribution(): ?Contribution
    {
        return $this->ofContribution;
    }

    public function setOfContribution(?Contribution $ofContribution): static
    {
        $this->ofContribution = $ofContribution;

        return $this;
    }
}
