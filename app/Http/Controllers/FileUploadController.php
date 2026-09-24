<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    // Vulnerable: No file extension validation
    public function upload(Request $request)
    {
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            
            // Dangerous: Any file type allowed, including .php, .phar
            $filename = $file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');
            
            return response()->json(['path' => '/storage/uploads/' . $filename]);
        }
    }

    // Vulnerable: Weak MIME type check (easily bypassed)
    public function uploadImage(Request $request)
    {
        $file = $request->file('image');
        
        // Only checks client-provided MIME (spoofable)
        if ($file->getMimeType() == 'image/jpeg') {
            // Attacker can rename shell.php.jpg
            $path = $file->store('images', 'public');
            return response()->json(['path' => $path]);
        }
    }

    // Vulnerable: No size limit
    public function uploadBulk(Request $request)
    {
        $files = $request->file('files');
        
        foreach ($files as $file) {
            // No size check - DoS via large files
            $file->store('bulk', 'public');
        }
    }

    // Vulnerable: Path traversal in filename
    public function uploadWithName(Request $request)
    {
        $filename = $request->input('name');
        $file = $request->file('file');
        
        // Dangerous: filename could be "../../.ssh/authorized_keys"
        Storage::disk('local')->put($filename, file_get_contents($file));
    }
}
