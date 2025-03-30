<?php

declare(strict_types=1);

namespace App\Permissions;

use App\Permissions\UserPermission;
use App\Permissions\RolePermission;
use ReflectionClass;

class Permissions
{
    /**
     * Get all permissions, optionally excluding specified permissions.
     *
     * @param array $excludes
     * @return array
     */
    public static function getAllPermissions(array $excludes = []): array
    {
        $groupPermissions = self::getAllPermissionsWithGrouping();
        $permissions = [];

        foreach ($groupPermissions as $group) {
            foreach ($group['permissions'] as $permissionName) {
                if (!in_array($permissionName, $excludes, true)) {
                    $permissions[] = $permissionName;
                }
            }
        }

        return $permissions;
    }

    /**
     * Get all permissions grouped by entity.
     * Dynamically loads permissions from classes like UserPermission, RolePermission.
     *
     * @return array
     */
    public static function getAllPermissionsWithGrouping(): array
    {
        // Define your permission classes dynamically
        $permissionClasses = [
            UserPermission::class,
            RolePermission::class,
        ];

        $groupPermissions = [];
        foreach ($permissionClasses as $class) {
            $groupName = self::getGroupNameFromClass($class);
            $permissions = self::getPermissionsFromClass($class);

            if ($groupName && $permissions) {
                $groupPermissions[] = [
                    'group_name' => $groupName,
                    'permissions' => $permissions,
                ];
            }
        }

        return $groupPermissions;
    }

    /**
     * Get permissions for a specific group dynamically from a class.
     *
     * @param string $class
     * @return array
     */
    private static function getPermissionsFromClass(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $permissions = [];
    
        // First, get CRUD permissions from the parent class (CrudPermission)
        $parentClass = $reflection->getParentClass();
        if ($parentClass && is_subclass_of($class, CrudPermission::class)) {
            $permissions[] = (new $class(''))->getPermissionKey('view');
            $permissions[] = (new $class(''))->getPermissionKey('create');
            $permissions[] = (new $class(''))->getPermissionKey('edit');
            $permissions[] = (new $class(''))->getPermissionKey('delete');
        }
    
        // Now, get the custom permissions defined in the child class
        foreach ($reflection->getConstants() as $name => $value) {
            if (strpos($name, '_PERMISSION') !== false) {
                $permissions[] = $value;
            }
        }
    
        return $permissions;
    }
    
    

    /**
     * Get the group name for a given permission class.
     *
     * @param string $class
     * @return string|null
     */
    private static function getGroupNameFromClass(string $class): ?string
    {
        $className = basename(str_replace('\\', '/', $class));
        $groupName = strtolower(string: str_replace('Permission', '', $className));

        return $groupName ?: null;
    }

    /**
     * Get all permissions including specified ones.
     *
     * @param array $includes
     * @return array
     */
    public static function getAllPermissionsIncluding(array $includes = []): array
    {
        $groupPermissions = self::getAllPermissionsWithGrouping();
        $permissions = [];

        foreach ($groupPermissions as $group) {
            foreach ($group['permissions'] as $permissionName) {
                if (in_array($permissionName, $includes, true)) {
                    $permissions[] = $permissionName;
                }
            }
        }

        return $permissions;
    }
}
