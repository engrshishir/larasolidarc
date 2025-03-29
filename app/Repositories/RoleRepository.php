<?php

declare(strict_types=1);

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository extends CrudRepository
{
    protected string $model = Role::class;

    public function getSuperAdminRoleId()
    {
        return $this->model::where('name', 'Super Admin')->firstOrFail()->id;
    }
}