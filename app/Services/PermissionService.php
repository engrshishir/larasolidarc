<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRepository;


class PermissionService extends CrudService
{
    public function __construct(private readonly PermissionRepository $permissionRepository)
    {
        parent::__construct(
            $permissionRepository,
            fn() => __('Role not found.')
        );
    }

    public function permissionGroup()
    {
        $transactions = $this->permissionRepository->all();
        return $transactions->groupBy('group_name');
    }
}
