<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoiceTraceController extends Controller
{
    public function trace(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $request['text'],
            'message' => 'testing',
        ], 200);
    }
}
