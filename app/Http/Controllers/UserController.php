<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Vulnerable: Storing password as plaintext without hashing
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password'); // Insecure: Missing Hash::make
        $user->save();

        return response()->json($user, 201);
    }
}
