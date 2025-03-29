<?php

declare(strict_types=1);

namespace App\Enum;

enum DeleteStatus: int
{
    case NOT_DELETED = 0;
    case SOFT_DELETE = 1;
    case PERMANENT_DELETE = 9;
}
