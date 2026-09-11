<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Insecure file upload vulnerability
     * Bug: No validation on file type, size, or content
     */
    public function upload(Request $request)
    {
        // VULNERABLE: No validation at all
        $file = $request->file('document');
        
        if ($file) {
            // VULNERABLE: Using original filename from user
            $filename = $file->getClientOriginalName();
            
            // VULNERABLE: Storing in public directory
            $path = $file->storeAs('public/uploads', $filename);
            
            return response()->json(['path' => $path]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
    
    /**
     * Path traversal vulnerability in file upload
     */
    public function uploadWithPath(Request $request)
    {
        $file = $request->file('document');
        $targetPath = $request->input('path'); // User-controlled path!
        
        // VULNERABLE: No path sanitization
        $fullPath = storage_path('app/' . $targetPath . '/' . $file->getClientOriginalName());
        
        $file->move(dirname($fullPath), basename($fullPath));
        
        return response()->json(['success' => true]);
    }
}
