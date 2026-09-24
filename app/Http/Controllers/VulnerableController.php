<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VulnerableController extends Controller
{
    // Bug 1: SQL Injection via raw query concatenation
    public function searchUser(Request $request)
    {
        $input = $request->input('query');
        $users = DB::select("SELECT * FROM users WHERE username = '" . $input . "'");
        return response()->json($users);
    }

    // Bug 2: Mass Assignment vulnerability
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all()); // Unprotected mass assignment allows updating is_admin / role
        return response()->json($user);
    }

    // Bug 3: Insecure Deserialization
    public function restoreSession(Request $request)
    {
        $payload = $request->input('session_data');
        $data = unserialize(base64_decode($payload)); // RCE vulnerability
        return response()->json($data);
    }

    // Safe Guard: Parameterized query (MUST NOT be flagged as SQLi)
    public function searchUserSafe(Request $request)
    {
        $input = $request->input('query');
        $users = DB::table('users')->where('username', $input)->get();
        return response()->json($users);
    }
}
