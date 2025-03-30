<?php
declare(strict_types=1);

namespace App\Permissions;

class UserPermission extends CrudPermission
{
    public function __construct()
    {
        parent::__construct('users');
    }
}