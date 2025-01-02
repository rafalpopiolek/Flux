<?php

declare(strict_types=1);

namespace App\Enum\Identity;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
