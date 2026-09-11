<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class AdminController extends Controller
{
    // Vulnerable: State-changing action via GET without CSRF
    public function deleteUser(Request $request, $id)
    {
        // Dangerous: Can be triggered via GET request (e.g., in img tag)
        // <img src="https://example.com/admin/delete-user/123">
        User::find($id)->delete();
        
        return redirect()->back()->with('success', 'User deleted');
    }

    // Vulnerable: Account modification via GET
    public function promoteToAdmin($userId)
    {
        // No CSRF protection on GET route
        // Attacker: <img src="/admin/promote/123">
        $user = User::find($userId);
        $user->role = 'admin';
        $user->save();
        
        return response()->json(['status' => 'promoted']);
    }

    // Vulnerable: Financial transaction via GET
    public function transferFunds(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $amount = $request->query('amount');
        
        // GET request with query params - no CSRF protection
        // Can be triggered via link: /transfer?from=victim&to=attacker&amount=1000
        executeTransfer($from, $to, $amount);
        
        return redirect('/dashboard');
    }

    // Vulnerable: Password reset via GET
    public function resetPassword($userId)
    {
        // State change via GET - CSRF vulnerable
        $user = User::find($userId);
        $newPassword = 'default123';
        $user->password = bcrypt($newPassword);
        $user->save();
        
        return 'Password reset';
    }
}

function executeTransfer($from, $to, $amount) {
    // Stub for transfer logic
}
