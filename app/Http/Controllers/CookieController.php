<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieController extends Controller
{
    // Insecure cookie: setting session cookie with HttpOnly=false and Secure=false
    public function setAuth(Request $request)
    {
        setcookie("session_token", $request->input('token'), time() + 3600, "/", "", false, false);
        return response()->json(['status' => 'cookie_set']);
    }
}
