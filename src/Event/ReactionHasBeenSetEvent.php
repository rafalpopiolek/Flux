<?php

declare(strict_types=1);

namespace App\Event;

final readonly class ReactionHasBeenSetEvent
{
    public function __construct(
        public int $authorId,
        public int $targetId,
        public string $target,
        public string $reactionType,
    ) {
    }
}
