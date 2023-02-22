<?php

namespace App\Datatables;

use Spatie\Permission\Models\Role;

class RoleDatatable extends Datatable
{
    protected string $model = Role::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Role $role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ];
        };
    }
}
