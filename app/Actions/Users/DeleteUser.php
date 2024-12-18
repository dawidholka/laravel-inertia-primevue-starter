<?php

namespace App\Actions\Users;

use App\Models\User;

class DeleteUser
{
    public function execute(User $user): void
    {
        $user->delete();
    }
}
