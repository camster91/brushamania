<?php 

namespace App\Http\Controllers\Traits;


use App\Rotarian;
use App\Presentation;
use App\RotarianRegistration;
use Illuminate\Support\Str;

trait RotarianTrait
{
	function checkRotarian($firstname, $lastname) {
		return (Rotarian::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->count());
	}

	function getRotarianUser($id) {
        return Rotarian::find($id)->user_id;
    }

	function addRotarian($request, $user_id, $year) {
		$rotarian = new Rotarian;
		$rotarian->user_id = $user_id;
		$rotarian->lastname = $request->contact_lastname;
		$rotarian->firstname = $request->contact_firstname;
		$rotarian->phone = $request->phone;
		$rotarian->contact_salutation = $request->contact_salutation;
		$rotarian->rotary_club = $request->club_name;
        $rotarian->is_organizing_committee = $request->is_organizing_committee;
        $rotarian->is_lootbag_committee = $request->is_lootbag_committee;
        $rotarian->is_pr_media_committee = $request->is_pr_media_committee;
        $rotarian->is_sponsor_committee = $request->is_sponsor_committee;
        $rotarian->is_telephoning_committee = $request->is_telephoning_committee;
        $rotarian->last_registration_year = $year;
        $rotarian->url_slug = Str::slug('rotarian '.$request->contact_firstname.' '.$request->contact_lastname, '-');
        if($rotarian->save()) {
        	return $rotarian;
        }
        return null;
	}

	function updateRotarian($request, $user_id, $rotarian_id, $year = null) {
		$rotarian = Rotarian::find($rotarian_id);
		$rotarian->user_id = $user_id;
		$rotarian->lastname = $request->contact_lastname;
		$rotarian->firstname = $request->contact_firstname;
		$rotarian->phone = $request->phone;
		$rotarian->contact_salutation = $request->contact_salutation;
		$rotarian->rotary_club = $request->club_name;
        $rotarian->is_organizing_committee = $request->is_organizing_committee;
        $rotarian->is_lootbag_committee = $request->is_lootbag_committee;
        $rotarian->is_pr_media_committee = $request->is_pr_media_committee;
        $rotarian->is_sponsor_committee = $request->is_sponsor_committee;
        $rotarian->is_telephoning_committee = $request->is_telephoning_committee;
        if(isset($year)) {
            $rotarian->last_registration_year = $year;
        }
        $rotarian->url_slug = Str::slug('rotarian '.$request->contact_firstname.' '.$request->contact_lastname, '-');
        if($rotarian->save()) {
        	return $rotarian;
        }
        return null;
	}

	function getRotarian($firstname, $lastname) {
		$rotarian = Rotarian::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->first();
		if(!empty($rotarian)) {
			return $rotarian->id;
		}
		return 0;
	}

	function checkIfRotarianIsRegistered($rotarian_id, $year) {
        $rotarian = Rotarian::find($rotarian_id);
        if(!empty($rotarian)) {
            return ($rotarian->last_registration_year == $year);
        }
        return 0;
	}

	function getRotariansList($year) {
		$rotarians = RotarianRegistration::rightJoin('rotarians', 'rotarians.id', 'rotarian_registrations.rotarian_id')
			->leftJoin('users', 'rotarians.user_id', '=', 'users.id')->selectRaw('rotarians.id as id, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian_name, users.email as email, rotarians.rotary_club as club, rotarians.url_slug as url_slug')
			->where('rotarian_registrations.year', $year)
			->orderBy('rotarians.updated_at', 'desc')->get();
		foreach($rotarians as $rotarian) {
			$presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.name as school, schools.url_slug as url_slug')->where('presentations.rotarian_id', $rotarian->id)->where('presentations.registration_year', $year)->whereNull('schools.invite_token')->orderBy('presentations.presentation_date', 'desc')->get();
			$rotarian->presentations = $presentations;
		}
		return $rotarians;
	}

	function showRotarian($rotarian) {
		$rotarian = Rotarian::leftJoin('users', 'rotarians.user_id', '=', 'users.id')
			->selectRaw('concat(rotarians.contact_salutation, " ", rotarians.firstname, " ", rotarians.lastname) as rotarian_name, rotarians.phone as phone, users.email as email, rotarians.rotary_club as club_name')
			->whereRaw("lower(rotarians.url_slug) = '".strtolower($rotarian)."'")->first();
		return $rotarian;
	}

	function editRotarian($rotarian) {
		$rotarian = Rotarian::leftJoin('users', 'rotarians.user_id', '=', 'users.id')
			->select('rotarians.id as id', 'users.firstname as contact_firstname', 'users.lastname as contact_lastname', 'rotarians.contact_salutation as contact_salutation', 'rotarians.phone as phone', 'users.email as email', 'rotarians.rotary_club as club_name', 'rotarians.is_organizing_committee as is_organizing_committee', 'rotarians.is_lootbag_committee as is_lootbag_committee', 'rotarians.is_pr_media_committee as is_pr_media_committee', 'rotarians.is_sponsor_committee as is_sponsor_committee', 'rotarians.is_telephoning_committee as is_telephoning_committee')
			->whereRaw("lower(rotarians.url_slug) = '".strtolower($rotarian)."'")->first();
		return $rotarian;		
	}

	function deleteRotarian($id) {
		return Rotarian::find($id)->delete();
	}

	function getRegisteredRotarians($year) {
		$rotarians = Rotarian::selectRaw('id, concat(firstname, " ", lastname) as name')
            ->where('last_registration_year', $year)->get();
        return $rotarians;
	}

	function getRegisteredRotariansCount($year) {
		return Rotarian::where('last_registration_year', $year)->count();
	}

	function addRotarianRegistration($id, $year) {
		$registration = new RotarianRegistration;
		$registration->rotarian_id = $id;
		$registration->year = $year;
		return $registration->save();
	}

	function exportRotarians($year) {
		$rotarians = RotarianRegistration::rightJoin('rotarians', 'rotarians.id', 'rotarian_registrations.rotarian_id')
			->leftJoin('users', 'rotarians.user_id', '=', 'users.id')
			->select('rotarians.phone as phone', 'rotarians.is_organizing_committee as is_organizing_committee', 'rotarians.is_lootbag_committee as is_lootbag_committee', 'rotarians.is_pr_media_committee as is_pr_media_committee', 'rotarians.is_sponsor_committee as is_sponsor_committee', 'rotarians.is_telephoning_committee as is_telephoning_committee')
			->selectRaw('rotarians.id as id, concat(rotarians.contact_salutation," ", rotarians.firstname, " ", rotarians.lastname) as rotarian_name, users.email as email, rotarians.rotary_club as club')
			->where('rotarian_registrations.year', $year)
			->orderBy('rotarians.updated_at', 'desc')->get();
		foreach($rotarians as $rotarian) {
			$presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.name as school')->where('presentations.rotarian_id', $rotarian->id)->where('presentations.registration_year', $year)->whereNull('schools.invite_token')->orderBy('presentations.presentation_date', 'desc')->get();
			$rotarian->presentations = $presentations;
		}
		return $rotarians;
	}

}