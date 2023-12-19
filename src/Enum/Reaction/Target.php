<?php

declare(strict_types=1);

namespace App\Enum\Reaction;

enum Target: string
{
    case POST = 'post';
    case COMMENT = 'comment';
}
