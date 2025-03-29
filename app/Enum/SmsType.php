<?php

declare(strict_types=1);

namespace App\Enum;

enum SmsType: string
{
    case MASKABLE = 'maskable';
    case NON_MASKABLE = 'non-maskable';
}
