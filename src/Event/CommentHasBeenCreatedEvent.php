<?php

declare(strict_types=1);

namespace App\Event;

final readonly class CommentHasBeenCreatedEvent
{
    public function __construct(
        public int $commentId,
        public int $postId,
    ) {
    }
}
