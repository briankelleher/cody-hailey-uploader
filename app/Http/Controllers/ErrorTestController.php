<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorTestController extends Controller {

    public function test413(Request $request) {
        return response()->json([
            'message' => 'Image upload failed.'
        ], 413);
    }

}