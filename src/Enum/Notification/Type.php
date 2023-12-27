<?php

declare(strict_types=1);

namespace App\Enum\Notification;

enum Type: string
{
    case NEW_FOLLOWER = 'new_follower';
    case NEW_POST = 'new_post';
    case NEW_COMMENT = 'new_comment';
    case NEW_REACTION = 'new_reaction';
}
