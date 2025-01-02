<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Notification\Type;
use App\Repository\NotificationRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sentNotifications')]
    private User $sender;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedNotifications')]
    private User $recipient;

    #[ORM\Column(type: 'string', enumType: Type::class)]
    private Type $type;

    #[Column(type: 'json')]
    private array $data;

    #[Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $readAt = null;

    #[Column(type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    public function __construct(
        User $sender,
        User $recipient,
        Type $type,
        array $data = [],
    ) {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->type = $type;
        $this->data = $data;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    public function isRead(): bool
    {
        return $this->readAt !== null;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setReadAt(DateTimeInterface $readAt): void
    {
        $this->readAt = $readAt;
    }
}
