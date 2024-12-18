<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class PermissionDTO extends Data
{
    public function __construct(
        public string $name,
        public string $guard_name,
    )
    {
    }
}
