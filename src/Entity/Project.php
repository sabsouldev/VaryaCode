<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    #[ORM\Column(type: Types::JSON)]
    private array $technologies = [];

    #[ORM\Column(length: 100)]
    private ?string $projectType = null;

    #[ORM\Column(length: 50)]
    private ?string $projectCategory = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $externalUrl = null;

    #[ORM\Column]
    private bool $isPublished = false;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getShortDescription(): ?string { return $this->shortDescription; }
    public function setShortDescription(string $shortDescription): static { $this->shortDescription = $shortDescription; return $this; }

    public function getImageFilename(): ?string { return $this->imageFilename; }
    public function setImageFilename(?string $imageFilename): static { $this->imageFilename = $imageFilename; return $this; }

    public function getTechnologies(): array { return $this->technologies; }
    public function setTechnologies(array $technologies): static { $this->technologies = $technologies; return $this; }

    public function getProjectType(): ?string { return $this->projectType; }
    public function setProjectType(string $projectType): static { $this->projectType = $projectType; return $this; }

    public function getProjectCategory(): ?string { return $this->projectCategory; }
    public function setProjectCategory(string $projectCategory): static { $this->projectCategory = $projectCategory; return $this; }

    public function getExternalUrl(): ?string { return $this->externalUrl; }
    public function setExternalUrl(?string $externalUrl): static { $this->externalUrl = $externalUrl; return $this; }

    public function isPublished(): bool { return $this->isPublished; }
    public function setIsPublished(bool $isPublished): static { $this->isPublished = $isPublished; return $this; }

    public function getPosition(): int { return $this->position; }
    public function setPosition(int $position): static { $this->position = $position; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getCategoryLabel(): string
    {
        return match ($this->projectCategory) {
            'client' => 'Projet client',
            'personal' => 'Projet personnel',
            'demo' => 'Démonstration',
            default => $this->projectCategory ?? '',
        };
    }

    public function getCategoryColor(): string
    {
        return match ($this->projectCategory) {
            'client' => 'purple',
            'personal' => 'blue',
            'demo' => 'green',
            default => 'gray',
        };
    }

    public function getTechnologiesAsString(): string
    {
    return implode(', ', $this->technologies);
    }

    public function setTechnologiesAsString(string $value): static
    {
        $this->technologies = array_filter(array_map('trim', explode(',', $value)));
        return $this;
    }
}
