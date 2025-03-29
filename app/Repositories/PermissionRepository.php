<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends CrudRepository
{
    protected string $model = Permission::class;
}