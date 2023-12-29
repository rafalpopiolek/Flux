<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class UserSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = null;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * @return User[]
     */
    public function users(): array
    {
        if ($this->query === null || strlen($this->query) < 3) {
            return [];
        }

        return $this->userRepository->findWithFilter($this->query);
    }
}
