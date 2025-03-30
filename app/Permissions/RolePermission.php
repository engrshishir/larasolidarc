<?php
declare(strict_types=1);
namespace App\Permissions;

class RolePermission extends CrudPermission
{
    public const SUPER_ADMIN_PERMISSION = 'roles.super_admin_assign_permission';


    public function __construct()
    {
        parent::__construct('roles');
    }


    // Override to provide custom error messages for role permissions
    protected function getCustomErrorMessages(): array
    {
        return [
            self::SUPER_ADMIN_PERMISSION => __("You are not super admin."),
        ];
    }
}
