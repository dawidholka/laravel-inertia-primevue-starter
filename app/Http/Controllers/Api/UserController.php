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
    ): JsonResponse
    {
        //abort_if(!auth()->user()->admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string']
        ]);

        $data = new UserDTO(
            name: $validated['name'],
            email: $validated['email'],
            password: Hash::make($validated['password'])
        );

        $user = $createUser->execute($data);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function update(
        Request    $request,
        User       $user,
        UpdateUser $updateUser
    ): JsonResponse
    {
        // abort_if(!auth()->user()->admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string']
        ]);

        $data = new UserDTO(
            name: $validated['name'],
            email: $validated['email'],
            password: isset($validated['password']) ? Hash::make($validated['password']) : null,
        );

        $updateUser->execute($user, $data);

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
