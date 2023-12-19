<?php

declare(strict_types=1);

namespace App\Enum\Reaction;

enum Type: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case LOVE = 'love';
    case LAUGH = 'laugh';
    case SAD = 'sad';
}
