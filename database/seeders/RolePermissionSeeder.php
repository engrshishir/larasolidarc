<?php

declare(strict_types=1);

namespace Database\Seeders;
use App\Permissions\Permissions;
use App\Permissions\UserPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->assignSuperAdminPermissions();
        $this->assignModeratorPermission();
    }

    private function assignRolePermission(string $roleCode, array $permissions = []): void
    {
        $role = Role::findByName($roleCode);
        
        // Ensure permissions exist in the database
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Assign the permissions to the role
        $role->givePermissionTo($permissions);
    }

    private function assignSuperAdminPermissions(): void
    {
        $this->assignRolePermission(
            \App\Enum\Role::SUPER_ADMIN->value, 
            Permissions::getAllPermissions([])
        );
    }

    private function assignModeratorPermission(): void
    {
        $userPermission = new UserPermission();

        $this->assignRolePermission(
            \App\Enum\Role::MODERATOR->value,
            Permissions::getAllPermissionsIncluding(
                [
                    $userPermission->getPermissionKey('create'),
                    $userPermission->getPermissionKey('view'),
                    $userPermission->getPermissionKey('edit'),
                    $userPermission->getPermissionKey('delete'),
                ]
            )
        );
    }
}
