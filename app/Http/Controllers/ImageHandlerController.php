<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandlerController extends Controller
{
    public function store(Request $request)
    {
    	// return response()->json(['request' => $request->image], 200);
    	$res = Storage::put('public', $request->image);
    	if(config('app.env') == 'local') {
    		$res = Str::replaceFirst('public', config('app.url').':8000/storage', $res);
    	} else {
    		$res = Str::replaceFirst('public', config('app.url').'/storage', $res);
    	}   	
    	return response()->json(['res' => $res], 200);
    }
}
