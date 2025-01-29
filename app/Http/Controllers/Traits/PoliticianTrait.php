<?php 

namespace App\Http\Controllers\Traits;
use Illuminate\Support\Str;

use App\Politician;


trait PoliticianTrait
{
	function checkPolitician($firstname, $lastname) {
		$count = Politician::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->count();
		if ($count >= 1) {
			$politician = Politician::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->first();
			if(!empty($politician)) {
				return $politician->id;
			}
			return 0;
		}
		return 0;
	}

	function addPolitician($request) {
		$politician = new Politician;
		$politician->firstname = $request->firstname;
		$politician->lastname = $request->lastname;
		$politician->email = $request->email;
		$politician->phone = $request->phone;
		$politician->title = $request->title;
		$politician->constituency = $request->constituency;
		$politician->city = $request->city;
		$politician->province = $request->province;
		$politician->url_slug = Str::slug('politician '.$request->firstname.' '.$request->lastname, '-');
		if($politician->save()) {
        	return $politician;
        }
        return null;
	}

	function updatePolitician($request, $politician_id) {
		$politician = Politician::find($politician_id);
		$politician->firstname = $request->firstname;
		$politician->lastname = $request->lastname;
		$politician->email = $request->email;
		$politician->phone = $request->phone;
		$politician->title = $request->title;
		$politician->constituency = $request->constituency;
		$politician->city = $request->city;
		$politician->province = $request->province;
		$politician->url_slug = Str::slug('politician '.$request->firstname.' '.$request->lastname, '-');
		if($politician->save()) {
        	return $politician;
        }
        return null;
	}

	function getPoliticiansList() {
		$politicians = Politician::selectRaw('id, concat(firstname, " ", lastname) as politician_name, email, phone, title, constituency, url_slug')->orderBy('created_at', 'desc')->get();
		return $politicians;
	}

	function deletePolitician($id) {
		return Politician::find($id)->delete();
	}

	function showPolitician($id) {
		$politician = Politician::selectRaw('id, concat(firstname, " ", lastname) as politician_name, email, phone, title, constituency, city, province')->where('url_slug', $id)->first();
		return $politician;
	}

	function editPolitician($id) {
		$politician = Politician::where('url_slug', $id)->first();
		return $politician;
	}

	function getPoliticiansEmailList($address) {
		if (!isset($address) || $address == "") {
			$politicians = Politician::selectRaw('id, concat(firstname, " ", lastname) as name, email')->orderBy('created_at', 'desc')->get();
		} else {
			$politicians = Politician::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('city', $address)->orWhere('province', $address)->orderBy('created_at', 'desc')->get();
		}
		return $politicians;
	}
}