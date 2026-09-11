<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    // Vulnerable: Open Redirect via user-supplied url parameter
    public function handleRedirect(Request $request)
    {
        $target = $request->input('url');
        return redirect($request->input('url'));
    }
}
