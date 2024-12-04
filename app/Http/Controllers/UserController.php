<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function home()
    {
        $activeUsers = User::count();
        return view(
            'home',
            compact(
                'activeUsers',
            )
        );
    }
}
