<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Insecure deserialization vulnerability
     * Bug: Unserialize user input allows object injection
     */
    public function import(Request $request)
    {
        $data = $request->input('data');
        
        // VULNERABLE: unserialize() on user input!
        // Attacker can inject malicious objects
        $imported = unserialize(base64_decode($data));
        
        // Process imported data
        foreach ($imported as $item) {
            \App\Models\Post::create($item);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Another deserialization vulnerability - session data
     */
    public function restoreSession(Request $request)
    {
        $sessionData = $request->input('session');
        
        // VULNERABLE: Deserializing session data from user
        $session = unserialize($sessionData);
        
        session()->put($session);
        
        return redirect('/dashboard');
    }
    
    /**
     * eval() vulnerability
     */
    public function calculate(Request $request)
    {
        $expression = $request->input('expression');
        
        // VULNERABLE: eval() on user input - RCE!
        $result = eval("return $expression;");
        
        return response()->json(['result' => $result]);
    }
}
