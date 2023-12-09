<?php

declare(strict_types=1);

namespace App\Enum\Post;

enum Status: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case REPORTED = 'reported';
    case REJECTED = 'rejected';
}
