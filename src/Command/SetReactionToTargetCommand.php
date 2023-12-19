<?php

declare(strict_types=1);

namespace App\Command;

final readonly class SetReactionToTargetCommand
{
    public function __construct(
        public int $authorId,
        public string $target,
        public int $targetId,
        public string $reactionType,
    ) {
    }
}
