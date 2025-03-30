<?php
declare(strict_types=1);

namespace App\Permissions;

class UserPermission extends CrudPermission
{
    public const ACCOUNT_ACTIVATION_PERMISSION = 'users.account_activation';

    public function __construct()
    {
        parent::__construct('users');
    }
    
    protected function getCustomErrorMessages(): array
    {
        return [
            self::ACCOUNT_ACTIVATION_PERMISSION => __("You are not allowed to do this action."),
        ];
    }
}