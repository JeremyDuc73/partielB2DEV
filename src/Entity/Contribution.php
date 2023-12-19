<?php

namespace App\Entity;

use App\Repository\ContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contribution:readSimple', 'contribution:readAll'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $ofEvent = null;

    #[ORM\Column(length: 255)]
    #[Groups(['contribution:readSimple', 'contribution:readAll'])]
    private ?string $product = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['contribution:readSimple', 'contribution:readAll'])]
    private ?Profile $ofProfile = null;

    #[ORM\OneToOne(inversedBy: 'forContribution', cascade: ['persist', 'remove'])]
    #[Groups(['contribution:readAll'])]
    private ?Suggestion $fromSuggestion = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getOfProfile(): ?Profile
    {
        return $this->ofProfile;
    }

    public function setOfProfile(?Profile $ofProfile): static
    {
        $this->ofProfile = $ofProfile;

        return $this;
    }

    public function getFromSuggestion(): ?Suggestion
    {
        return $this->fromSuggestion;
    }

    public function setFromSuggestion(?Suggestion $fromSuggestion): static
    {
        $this->fromSuggestion = $fromSuggestion;

        return $this;
    }
}
