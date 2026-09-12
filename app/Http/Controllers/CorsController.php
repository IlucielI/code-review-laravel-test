<?php

namespace App\Http\Controllers;

class CorsController extends Controller
{
    // Insecure CORS: Wildcard origin with credentials allowed
    public function handle()
    {
        return response()->json(['data' => 'secret'])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Content-Security-Policy', "default-src 'self'; script-src * 'unsafe-eval';");
    }
}
