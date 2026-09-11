<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * SSTI vulnerability - Server-Side Template Injection
     * Bug: User input directly interpolated into template string
     */
    public function render(Request $request)
    {
        $template = $request->input('template');
        
        // VULNERABLE: Direct template rendering without sanitization
        $blade = \Blade::compileString($template);
        
        return eval('?>' . $blade);
    }
    
    /**
     * Another SSTI variant - dynamic view path
     */
    public function dynamicView(Request $request)
    {
        $viewName = $request->input('view');
        
        // VULNERABLE: User controls view path
        return view($viewName);
    }
}
