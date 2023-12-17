<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Reaction\Target;
use App\Enum\Reaction\Type;
use App\Repository\ReactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
class Reaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: 'string', enumType: Type::class)]
    private ?Type $type;

    #[ORM\Column(type: 'integer')]
    private ?int $targetId;

    #[ORM\Column(type: 'string', enumType: Target::class)]
    private ?Target $targetType;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): void
    {
        $this->type = $type;
    }

    public function getTargetId(): ?int
    {
        return $this->targetId;
    }

    public function setTargetId(?int $targetId): void
    {
        $this->targetId = $targetId;
    }

    public function getTargetType(): ?Target
    {
        return $this->targetType;
    }

    public function setTargetType(?Target $targetType): void
    {
        $this->targetType = $targetType;
    }
}
