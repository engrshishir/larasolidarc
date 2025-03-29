<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService extends CrudService
{
    public function __construct(private readonly RoleRepository $roleRepository)
    {
        parent::__construct(
            $roleRepository,
            fn() => __('Role not found.')
        );
    }
}
