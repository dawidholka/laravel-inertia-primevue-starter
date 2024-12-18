<?php

namespace App\Http\Controllers\Api;

use App\Actions\Roles\CreateRole;
use App\Actions\Roles\UpdateRole;
use App\DTOs\RoleDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Datatables\RoleDatatable;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(
        Request       $request,
        RoleDatatable $datatable,
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function store(
        RoleRequest $request,
        CreateRole  $createRole,
    ): JsonResponse
    {
        $data = new RoleDTO(
            name: $request->name,
            guard_name: $request->guard_name,
        );

        $role = $createRole->execute($data);

        return $this->sendSuccess($role);
    }

    public function update(
        RoleRequest $request,
        Role        $role,
        UpdateRole  $updateRole,
    ): JsonResponse
    {
        $data = new RoleDTO(
            name: $request->name,
            guard_name: $request->guard_name,
        );

        $updateRole->execute($role, $data);

        return $this->sendSuccess("Role updated successfully");
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return $this->sendSuccess("Role deleted successfully");
    }
}
