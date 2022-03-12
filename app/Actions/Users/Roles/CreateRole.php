<?php

namespace App\Actions\Users;

use App\DTOs\RoleDTO;
use Spatie\Permission\Models\Role;

class CreateRole
{
    public function execute(RoleDTO $data): Role
    {
        $role = new Role();
        $role->name = $data->name;
        $role->guard_name = $data->guard_name;

        $role->save();

        return $role;
    }
}
