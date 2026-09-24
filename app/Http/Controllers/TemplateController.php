<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class TemplateController extends Controller
{
    // Vulnerable: Server-Side Template Injection via Blade
    public function render(Request $request)
    {
        $userTemplate = $request->input('template');

        // Dangerous: User-controlled template rendering
        // Attacker can inject: {{ system('whoami') }}
        $compiled = Blade::compileString($userTemplate);
        eval('?>' . $compiled);
    }

    // Vulnerable: Dynamic view with user input
    public function preview(Request $request)
    {
        $content = $request->input('content');

        // Dangerous: @php directive allows arbitrary code
        // Attacker: "@php system('rm -rf /'); @endphp"
        $template = "@php echo 'User says: '; @endphp {!! $content !!}";
        
        return view('dynamic', ['template' => $template]);
    }

    // Vulnerable: eval() with user input in Blade
    public function calculate(Request $request)
    {
        $expression = $request->input('expr');

        // Extremely dangerous
        return view('result', [
            'result' => eval("return $expression;")
        ]);
    }
}
