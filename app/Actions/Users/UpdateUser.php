<?php

namespace App\Actions\Users;

use App\DTOs\UserDTO;
use App\Models\User;

class UpdateUser
{
    public function execute(User $user, UserDTO $data): User
    {
        $user->name = $data->name;
        $user->email = $data->email;
        if ($data->password) {
            $user->password = $data->password;
        }

        $user->save();

        return $user;
    }
}
