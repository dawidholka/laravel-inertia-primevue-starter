<?php

namespace App\Datatables;

use Spatie\Permission\Models\Permission;

class PermissionDatatable extends Datatable
{
    protected string $model = Permission::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Permission $permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
            ];
        };
    }
}
