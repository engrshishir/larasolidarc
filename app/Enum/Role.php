<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: string
{
    case ADMIN = 'Admin';
    case SUPER_ADMIN = 'Super Admin';
    case MODERATOR = 'Moderator';
    case CASHIER = 'Cashier';
}
