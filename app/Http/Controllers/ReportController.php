<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $posts = Post::all();

        retrun response()->json(['total' => $posts->count()]);
    }
}
