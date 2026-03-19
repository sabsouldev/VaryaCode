<?php

namespace App\Entity;

use App\Repository\DevisRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRequestRepository::class)]
class DevisRequest
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_QUOTED = 'quoted';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contactName = null;

    #[ORM\Column(length: 255)]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 100)]
    private ?string $structureType = null;

    #[ORM\Column]
    private bool $hasExistingSite = false;

    #[ORM\Column(length: 255)]
    private ?string $mainObjective = null;

    #[ORM\Column(length: 100)]
    private ?string $estimatedPages = null;

    #[ORM\Column(length: 50)]
    private ?string $needsAutonomy = null;

    #[ORM\Column(type: Types::JSON)]
    private array $features = [];

    #[ORM\Column(length: 100)]
    private ?string $budget = null;

    #[ORM\Column(length: 100)]
    private ?string $timeline = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additionalMessage = null;

    #[ORM\Column(length: 255)]
    private ?string $suggestedOffer = null;

    #[ORM\Column(length: 50)]
    private string $status = self::STATUS_NEW;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getContactName(): ?string { return $this->contactName; }
    public function setContactName(string $contactName): static { $this->contactName = $contactName; return $this; }

    public function getContactEmail(): ?string { return $this->contactEmail; }
    public function setContactEmail(string $contactEmail): static { $this->contactEmail = $contactEmail; return $this; }

    public function getContactPhone(): ?string { return $this->contactPhone; }
    public function setContactPhone(?string $contactPhone): static { $this->contactPhone = $contactPhone; return $this; }

    public function getStructureType(): ?string { return $this->structureType; }
    public function setStructureType(string $structureType): static { $this->structureType = $structureType; return $this; }

    public function hasExistingSite(): bool { return $this->hasExistingSite; }
    public function setHasExistingSite(bool $hasExistingSite): static { $this->hasExistingSite = $hasExistingSite; return $this; }

    public function getMainObjective(): ?string { return $this->mainObjective; }
    public function setMainObjective(string $mainObjective): static { $this->mainObjective = $mainObjective; return $this; }

    public function getEstimatedPages(): ?string { return $this->estimatedPages; }
    public function setEstimatedPages(string $estimatedPages): static { $this->estimatedPages = $estimatedPages; return $this; }

    public function getNeedsAutonomy(): ?string { return $this->needsAutonomy; }
    public function setNeedsAutonomy(string $needsAutonomy): static { $this->needsAutonomy = $needsAutonomy; return $this; }

    public function getFeatures(): array { return $this->features; }
    public function setFeatures(array $features): static { $this->features = $features; return $this; }

    public function getBudget(): ?string { return $this->budget; }
    public function setBudget(string $budget): static { $this->budget = $budget; return $this; }

    public function getTimeline(): ?string { return $this->timeline; }
    public function setTimeline(string $timeline): static { $this->timeline = $timeline; return $this; }

    public function getAdditionalMessage(): ?string { return $this->additionalMessage; }
    public function setAdditionalMessage(?string $additionalMessage): static { $this->additionalMessage = $additionalMessage; return $this; }

    public function getSuggestedOffer(): ?string { return $this->suggestedOffer; }
    public function setSuggestedOffer(string $suggestedOffer): static { $this->suggestedOffer = $suggestedOffer; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function __toString(): string
    {
        return sprintf('%s — %s', $this->contactName, $this->suggestedOffer);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_NEW => 'Nouveau',
            self::STATUS_CONTACTED => 'Contacté',
            self::STATUS_QUOTED => 'Devis envoyé',
            self::STATUS_WON => 'Gagné',
            self::STATUS_LOST => 'Perdu',
            default => $this->status,
        };
    }
}
