<?php

namespace App\Actions\Users;

use Spatie\Permission\Models\Role;

class DeleteRole
{
    public function execute(Role $role)
    {
        $role->delete();
    }
}
