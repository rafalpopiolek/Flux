<?php

declare(strict_types=1);

namespace App\Event;

use DateTimeInterface;

final readonly class UserHasBeenFollowedEvent
{
    public function __construct(
        public int $follower,
        public int $followee,
        public DateTimeInterface $occurredAt,
    ) {
    }
}
