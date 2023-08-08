<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProtectedSampleController extends Controller
{
    //
    public function sampleProtected()
    {
        return response()->json(['msg' => 'Success']);
    }
}
