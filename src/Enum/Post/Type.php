<?php

declare(strict_types=1);

namespace App\Enum\Post;

enum Type: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
