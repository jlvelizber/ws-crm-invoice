<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WPApiController extends Controller
{
    public function checkHealth()
    {
        return response()->json(['status' => 'ok'], 200);
    }
}
