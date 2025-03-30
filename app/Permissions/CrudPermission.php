<?php
declare(strict_types=1);

namespace App\Permissions;

use Illuminate\Http\Request;

abstract class CrudPermission extends PermissionChecker
{
    protected string $entity;

    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Generate permission key dynamically based on the entity
     * 
     * @param string $action
     * @return string
     */
    public function getPermissionKey(string $action): string
    {
        return "{$this->entity}.{$action}";
    }

    /**
     * Get the error message for a given permission key dynamically based on the entity
     *
     * @param string $permissionKey
     * @return string
     */
    protected function getErrorMessages(string $permissionKey): string
    {
        $errorMessages = [
            $this->getPermissionKey('view') => __("You do not have permission to view {$this->entity}."),
            $this->getPermissionKey('create') => __("You are not authorized to create new {$this->entity}."),
            $this->getPermissionKey('edit') => __("You cannot edit {$this->entity}."),
            $this->getPermissionKey('delete') => __("You do not have the necessary permission to delete {$this->entity}."),
        ];

        // If subclass wants to override, it can provide custom messages
        $customMessages = $this->getCustomErrorMessages();

        // Merge the default error messages with any custom messages
        $errorMessages = array_merge($errorMessages, $customMessages);

        return $errorMessages[$permissionKey] ?? __('Unauthorized action');
    }



    /**
     * This method allows subclasses to provide custom error messages.
     * It can be overridden in the subclass to define specific messages for each permission.
     *
     * @return array
     */
    protected function getCustomErrorMessages(): array
    {
        return [];
    }

    // CRUD permission checking methods
    public function canView(): bool
    {
        if (!$this->hasPermissions($this->getPermissionKey('view'))) {
            $this->throwUnAuthorizedException($this->getErrorMessages($this->getPermissionKey('view')));
        }

        return true;
    }

    public function canCreate(Request $request): bool
    {
        if (!$this->hasPermissions($this->getPermissionKey('create'))) {
            $this->throwUnAuthorizedException($this->getErrorMessages($this->getPermissionKey('create')));
        }

        return true;
    }

    public function canEdit(Request $request, int $id): bool
    {
        if (!$this->hasPermissions($this->getPermissionKey('edit'))) {
            $this->throwUnAuthorizedException($this->getErrorMessages($this->getPermissionKey('edit')));
        }

        return true;
    }

    public function canDelete(int $id): bool
    {
        if (!$this->hasPermissions($this->getPermissionKey('delete'))) {
            $this->throwUnAuthorizedException($this->getErrorMessages($this->getPermissionKey('delete')));
        }

        return true;
    }
}
