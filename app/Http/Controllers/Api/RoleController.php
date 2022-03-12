<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Datatables\RoleDatatable;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request, RoleDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }
    public function store(RoleRequest $request){
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();

        return $this->sendSuccess($role);
    }
    public function update(RoleRequest $request, Role $role){
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();

        return $this->sendSuccess("Role updated successfully");
    }

    public function destroy(Role $role){
        $role->delete();
        return $this->sendSuccess("Role deleted successfully");
    }
}
