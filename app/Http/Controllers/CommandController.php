<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommandController extends Controller
{
    // Command Injection via shell_exec with unescaped user parameter
    public function run(Request $request)
    {
        $cmd = $request->input('cmd');
        return shell_exec("ping -c 1 " . $cmd);
    }
}
