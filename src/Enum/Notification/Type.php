<?php

declare(strict_types=1);

namespace App\Enum\Notification;

enum Type: string
{
    case NEW_FOLLOWER = 'new follower';
    case NEW_POST = 'new post';
    case NEW_COMMENT = 'new comment';
    case NEW_REACTION = 'new reaction';
}
