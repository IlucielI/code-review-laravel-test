<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * CSRF on GET request vulnerability
     * Bug: State-changing operation on GET allows CSRF via img/link
     */
    public function deletePost(Request $request, $id)
    {
        // VULNERABLE: DELETE operation via GET request!
        // Can be triggered via: <img src="/admin/posts/123/delete">
        
        $post = Post::findOrFail($id);
        $post->delete();
        
        return redirect()->back()->with('success', 'Post deleted');
    }
    
    /**
     * Another CSRF via GET - user privilege escalation
     */
    public function promoteUser(Request $request, $userId)
    {
        // VULNERABLE: Privilege escalation via GET
        // Can be triggered: <a href="/admin/users/123/promote">Click here</a>
        
        $user = \App\Models\User::findOrFail($userId);
        $user->role = 'admin';
        $user->save();
        
        return redirect()->back()->with('success', 'User promoted');
    }
    
    /**
     * Missing CSRF token verification
     */
    public function updateSettings(Request $request)
    {
        // VULNERABLE: No CSRF token check on POST
        // Should use VerifyCsrfToken middleware
        
        $settings = $request->all();
        \Cache::put('app_settings', $settings);
        
        return response()->json(['success' => true]);
    }
}
