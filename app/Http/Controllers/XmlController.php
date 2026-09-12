<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XmlController extends Controller
{
    // XXE vulnerability via simplexml_load_string with entity expansion enabled
    public function parse(Request $request)
    {
        $xml = $request->input('xml');
        $data = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOENT);
        return response()->json($data);
    }
}
