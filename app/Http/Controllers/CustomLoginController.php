<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\YearTrait;
use App\ChildParent;
use Propaganistas\LaravelPhone\PhoneNumber;
use Auth;

class CustomLoginController extends Controller
{
	use YearTrait;

	public function loginForm()
	{
		return view('auth.parent_login');
	}

    public function login(Request $request)
    {
		$formatted_phone = PhoneNumber::make($request->phone, 'CA');
    	$parent = ChildParent::where('formatted_phone', $formatted_phone)->where('last_registration_year', $this->getActiveYear())->first();
    	// $parent = ChildParent::where('phone', $request->phone)->where('last_registration_year', $this->getActiveYear())->first();
    	if (empty($parent)) {
    		return redirect('parent-login')->with('message', 'The number entered is not registered for Brush-a-mania '. $this->getActiveYear().'. ');
    	}
		if (Auth::loginUsingId($parent->user->id)) {
			return redirect('home');
		}
    }
}
