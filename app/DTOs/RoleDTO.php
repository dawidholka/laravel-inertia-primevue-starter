<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class RoleDTO extends DataTransferObject
{
    public string $name;
    public string $email;
    public string $guard_name;
}
