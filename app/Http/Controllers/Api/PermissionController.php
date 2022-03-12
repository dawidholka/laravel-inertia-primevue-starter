<?php

namespace App\Http\Controllers\Api;

use App\Datatables\PermissionDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request, PermissionDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }
    public function store(PermissionRequest $request){
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

        return $this->sendSuccess($permission);
    }
    public function update(PermissionRequest $request, Permission $permission){
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

        return $this->sendSuccess("Role updated successfully");
    }

    public function destroy(Permission $permission){
        $permission->delete();
        return $this->sendSuccess("Role deleted successfully");
    }
}
