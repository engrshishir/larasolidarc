<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UserRepository extends CrudRepository
{
    protected string $model = User::class;
}