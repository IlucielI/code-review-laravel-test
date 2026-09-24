<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    // Vulnerable: unserialize() on user input
    public function processData(Request $request)
    {
        $serializedData = $request->input('data');
        
        // Extremely dangerous - Remote Code Execution via object injection
        // Attacker can craft malicious serialized object with __wakeup() magic method
        $data = unserialize($serializedData);
        
        return response()->json(['processed' => $data]);
    }

    // Vulnerable: Cookie deserialization
    public function loadUserPreferences(Request $request)
    {
        $prefs = $request->cookie('preferences');
        
        // Deserializing untrusted cookie data
        $preferences = unserialize(base64_decode($prefs));
        
        return view('settings', ['prefs' => $preferences]);
    }

    // Vulnerable: Session data deserialization
    public function restoreSession(Request $request)
    {
        $sessionData = $request->input('session');
        
        // Blindly deserializing session from client
        $_SESSION = unserialize($sessionData);
        
        return redirect('/dashboard');
    }

    // Vulnerable: Cache value deserialization
    public function getCachedData($key)
    {
        // If cache stores serialized data from untrusted source
        $cached = file_get_contents("/tmp/cache_" . $key);
        
        // Attacker can poison cache with malicious serialized object
        $data = unserialize($cached);
        
        return response()->json($data);
    }

    // Example of exploitable class (POP chain)
    // Attacker crafts: O:10:"FileWriter":1:{s:4:"path";s:10:"/etc/passwd";}
}

class FileWriter
{
    private $path;
    private $content;

    public function __wakeup()
    {
        // Automatically called during unserialize()
        // Attacker controls $path and $content
        file_put_contents($this->path, $this->content);
    }
}
