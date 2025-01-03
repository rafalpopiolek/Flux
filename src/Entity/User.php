<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(fields: ['email'], name: 'email_idx')]
#[ORM\Index(fields: ['firstName'], name: 'first_name_idx')]
#[ORM\Index(fields: ['lastName'], name: 'last_name_idx')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Profile::class, cascade: ['persist', 'remove'])]
    private Profile $profile;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    #[ORM\JoinTable(name: 'followers')]
    private Collection $follows;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follows')]
    private Collection $followers;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, cascade: ['remove'])]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Notification::class)]
    private Collection $sentNotifications;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Notification::class)]
    private Collection $receivedNotifications;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->sentNotifications = new ArrayCollection();
        $this->receivedNotifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @see UserInterface */
    public function getRoles(): array
    {
        return array_unique(
            $this->roles
        );
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /** @see PasswordAuthenticatedUserInterface */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /** @see UserInterface */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Post> */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        if ($this !== $profile->getUser()) {
            $profile->setUser($this);
        }
        $this->profile = $profile;

        return $this;
    }

    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function follow(self $user): static
    {
        if (! $this->follows->contains($user)) {
            $this->follows[] = $user;
        }

        return $this;
    }

    public function unfollow(self $user): static
    {
        $this->follows->removeElement($user);

        return $this;
    }

    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function getSentNotifications(): Collection
    {
        return $this->sentNotifications;
    }

    public function getReceivedNotifications(): Collection
    {
        return $this->receivedNotifications;
    }

    public function unreadNotifications(): Collection
    {
        return $this->receivedNotifications->filter(fn (Notification $notification) => ! $notification->isRead());
    }
}
