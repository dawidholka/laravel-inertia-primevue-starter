<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Users\CreateUser;
use App\Actions\Users\UpdateUser;
use App\Datatables\UserDatatable;
use App\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request, UserDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);
        return response()->json($data);
    }
    public function store(
        Request    $request,
        CreateUser $createUser
    )
    {
        //abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string']
        ]);

        $user = $createUser->execute(new UserDTO([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]));

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => $user
        ]);
    }

    public function update(
        Request    $request,
        User       $user,
        UpdateUser $updateUser
    ): JsonResponse
    {
        // abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string']
        ]);

        $updateUser->execute($user, new UserDTO([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'] ?
                Hash::make($request['password']) : null,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        // abort_if(!auth()->user()->admin, 403);

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
