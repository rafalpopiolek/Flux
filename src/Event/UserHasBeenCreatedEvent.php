<?php

declare(strict_types=1);

namespace App\Event;

final readonly class UserHasBeenCreatedEvent
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
