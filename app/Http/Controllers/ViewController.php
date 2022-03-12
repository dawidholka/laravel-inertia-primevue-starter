<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ViewController extends Controller
{
    public function users(): Response
    {
        return Inertia::render('Users/Index');
    }

    public function roles() : Response
    {
        return Inertia::render('Users/Roles/Index');
    }

    public function permissions() : Response
    {
        return Inertia::render('Users/Permissions/Index');
    }
}
