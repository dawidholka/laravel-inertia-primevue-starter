<?php

namespace App\Actions\Users;

use App\DTOs\RoleDTO;
use Spatie\Permission\Models\Role;

class UpdateRole
{
    public function execute(Role $role, RoleDTO $data): Role
    {
        $role->name = $data->name;
        $role->guard_name = $data->guard_name;
        $role->save();

        return $role;
    }
}
