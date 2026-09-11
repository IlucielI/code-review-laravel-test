<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Vulnerable: Insecure Direct Object Reference (IDOR) - deleting comment without checking ownership
    public function destroy($id)
    {
        // Deleting resource directly without auth or ownership verification
        Comment::findOrFail($id)->delete();
        return response()->json(['message' => 'Comment deleted without auth']);
    }
}
