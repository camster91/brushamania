<?php 

namespace App\Http\Controllers\Traits;


use App\School;
use App\SchoolRegistration;
use App\Presentation;
use App\User;
use Illuminate\Support\Str;
use App\Guest;
use Auth;

trait SchoolTrait
{
	function listAllSchools() {
		return School::orderBy('name')->get();
	}
	function checkSchool($name, $city) {
		return (School::whereRaw("lower(name) = '".strtolower($name)."'")->whereRaw("lower(city) = '".strtolower($city)."'")->count() >= 1);
	}

	function autofillSchool($city, $school) {
		$school_data = School::whereRaw("lower(name) = '".strtolower($school)."'")->whereRaw("lower(city) = '".strtolower($city)."'")->first();
		return $school_data;
	}

	function addSchool($request, $user_id, $year) {
		$school = new School;
		$school->user_id = $user_id;
		$school->last_registration_year = $year;
		$school->name = $request->name;
		$school->address = $request->address;
		$school->city = $request->city;
		$school->province = $request->province;
		$school->postal_code = $request->postal_code;
		$school->phone = $request->phone;
		$school->contact_salutation = $request->contact_salutation;
		$school->requested_dentist = $request->requested_dentist;
		$school->invite_token = $request->invite_token;
		$school->url_slug = Str::slug('school '.$request->name.' '.$request->address.' '.$request->city, '-');
		if($school->save()) {
			return $school;
		}
		return null;
	}


	function updateSchool($request, $user_id, $school_id, $year = null) {
		$school = School::find($school_id);
		$school->user_id = $user_id;
		if(isset($year)) {
			if ($school->last_registration_year < $year) {
				$school->last_registration_year = $year;
			}
		}
		$school->name = $request->name;
		$school->address = $request->address;
		$school->city = $request->city;
		$school->province = $request->province;
		$school->postal_code = $request->postal_code;
		$school->phone = $request->phone;
		$school->contact_salutation = $request->contact_salutation;
		$school->requested_dentist = $request->requested_dentist;
		$school->invite_token = $request->invite_token;
		$school->url_slug = Str::slug('school '.$request->name.' '.$request->address.' '.$request->city, '-');
		if($school->save()) {
			if (Auth::check()) {
				if(Auth::user()->role == 'school' && !isset($year)) {
					$presentation = Presentation::where('school_id', $school->id)->orderBy('created_at', 'desc')->first();
					$presentation->number_of_classes = $request->number_of_classes;
					$presentation->number_of_students = $request->number_of_students;
					$presentation->save();
				}
			}
			return $school;
		}
		return null;
	}

	function getSchoolRequest($invite_token) {
		$school = School::whereRaw("lower(invite_token) = '".strtolower($invite_token)."'")->first();
		if(!empty($school)) {
			return $school;
		}
		return null;
	}

	function getSchool($name, $city = "") {
		$school = new School;
		if($city == "") {
			$school = School::whereRaw("lower(name) = '".strtolower($name)."'")->first();
		} else {
			$school = School::whereRaw("lower(name) = '".strtolower($name)."'")->whereRaw("lower(city) = '".strtolower($city)."'")->first();
		}
		if(!empty($school)) {
			return $school->id;
		}
		return 0;
	}

	function getSchoolUser($id) {
		return School::find($id)->user_id;
	}

	function checkIfSchoolIsRegistered($school_id, $active_year) {
		$school = School::find($school_id);
		if(!empty($school)) {
			return ($school->last_registration_year == $active_year);
		}
		return 0;
	}

	function getSchoolList($year) {
		if($year != 0) {
			$schools = SchoolRegistration::rightJoin('schools', 'schools.id', 'school_registrations.school_id')
				->leftJoin('users', 'schools.user_id', '=', 'users.id')
				->select('schools.id as id','schools.name as school_name', 'schools.url_slug as url_slug', 'users.email as email')
				->where('school_registrations.year', $year)
				->whereNull('schools.invite_token')
				->orderBy('schools.updated_at', 'desc')->get();
			foreach($schools as $school) {
				$presentation = Presentation::leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')
					->leftJoin('rotarians', 'presentations.rotarian_id', '=', 'rotarians.id')
					->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian, dentists.url_slug as dentist_url_slug, rotarians.url_slug as rotarian_url_slug, presentations.number_of_classes as total_classes, presentations.number_of_students as total_students')
					->where('presentations.school_id', $school->id)->where('presentations.registration_year', $year)->first();
				$school->presentation = $presentation;
			}
		} else {
			$schools = School::select('schools.id as id','schools.name as school_name', 'schools.url_slug as url_slug', 'schools.last_registration_year as last_registration_year')
				->orderBy('schools.updated_at', 'desc')->get();
			foreach($schools as $school) {
				if($school->last_registration_year != null) {
					$presentation = Presentation::leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')
					->leftJoin('rotarians', 'presentations.rotarian_id', '=', 'rotarians.id')
					->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian, dentists.url_slug as dentist_url_slug, rotarians.url_slug as rotarian_url_slug, presentations.number_of_classes as total_classes, presentations.number_of_students as total_students')
					->where('presentations.school_id', $school->id)->where('presentations.registration_year', $school->last_registration_year)->first();
				} else {
					$presentation = null;
				}
				$school->presentation = $presentation;
			}
		}

		
		
		return $schools;
	}

	function showSchool($school_slug, $year) {
		$school = School::leftJoin('users', 'schools.user_id', '=', 'users.id')
			->leftJoin('presentations', 'schools.id', '=', 'presentations.school_id')
			->leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')
			->leftJoin('rotarians', 'presentations.rotarian_id', '=', 'rotarians.id')
			->selectRaw('presentations.id as presentation_id, schools.name as school_name, schools.address as address, schools.city as city, schools.province as province, schools.postal_code as postal_code, schools.phone as phone, concat(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as school_contact, presentations.number_of_classes as number_of_classes, presentations.number_of_students as number_of_students, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist_name, dentists.phone as dentist_phone, rotarians.phone as rotarian_phone, dentists.website as dentist_website, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian_name, dentists.user_id as dentist_user_id, rotarians.user_id as rotarian_user_id, dentists.contact_salutation as dentist_contact_salutation, users.email as email')
			->whereRaw("lower(schools.url_slug) = '".strtolower($school_slug)."'")->where('presentations.registration_year', $year)->first();
		if(empty($school)) {
			$school = School::leftJoin('users', 'schools.user_id', '=', 'users.id')
			->selectRaw('schools.name as school_name, schools.address as address, schools.city as city, schools.postal_code as postal_code, schools.phone as phone, concat(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as school_contact, schools.province as province, users.email as email')
			->whereRaw("lower(schools.url_slug) = '".strtolower($school_slug)."'")->first();
			$school->presentation_date = '';
		} else {
			$dentist_user = User::find($school->dentist_user_id);
			if(!empty($dentist_user)) {
				$school->dentist_email = $dentist_user->email;
				$school->dentist_contact = $school->dentist_contact_salutation.' '.$dentist_user->firstname.' '.$dentist_user->lastname;
			}
			$rotarian_user = User::find($school->rotarian_user_id);
			if(!empty($rotarian_user)) {
				$school->rotarian_email = $rotarian_user->email;
			}
			$guests = Guest::where('presentation_id', $school->presentation_id)->get();
			$school->guests = $guests->map(function($guest) {
				return $guest->name;
			});
			
		}
		return $school;
	}

	function editSchool($school_slug, $year) {
		$school = School::leftJoin('users', 'schools.user_id', '=', 'users.id')
			->leftJoin('presentations', 'schools.id', '=', 'presentations.school_id')
			->select('schools.*', 'users.email as email', 'users.firstname as contact_firstname', 'users.lastname as contact_lastname', 'presentations.number_of_classes as number_of_classes', 'presentations.number_of_students as number_of_students')
			->whereRaw("lower(url_slug) = '".strtolower($school_slug)."'")
			->where('presentations.registration_year', $year)->first();
		if(empty($school)) {
			$school = School::leftJoin('users', 'schools.user_id', '=', 'users.id')
			->select('schools.*', 'users.email as email', 'users.firstname as contact_firstname', 'users.lastname as contact_lastname')
			->whereRaw("lower(url_slug) = '".strtolower($school_slug)."'")->first();
			$school->is_registered = false;
			return $school;
		}
		$school->is_registered = true;
		return $school;
	}

	function deleteSchool($id) {
		return School::find($id)->delete();
	}

	function getRegisteredSchoolsCount($year) {
		return School::where('last_registration_year', $year)->count();
	}

	function addSchoolRegistration($id, $year) {
		$registration = new SchoolRegistration;
		$registration->school_id = $id;
		$registration->year = $year;
		return $registration->save();
	}

	function exportSchools($year) {
		if($year != 0) {
			$schools = SchoolRegistration::rightJoin('schools', 'schools.id', 'school_registrations.school_id')
			->leftJoin('users', 'schools.user_id', '=', 'users.id')
			->select('schools.id as id','schools.name as school_name', 'schools.url_slug as url_slug', 'users.email as email', 'schools.address as address', 'schools.city as city', 'schools.postal_code as postal_code', 'schools.phone as phone', 'schools.requested_dentist as requested_dentist', 'schools.province as province', 'schools.last_registration_year as last_registration_year')
			->selectRaw('CONCAT(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as contact')
			->where('school_registrations.year', $year)
			->whereNull('schools.invite_token')
			->orderBy('schools.updated_at', 'desc')->get();
			foreach($schools as $school) {
				$school->school_slug = Str::slug($school->school_name.' '.$school->city, '+');
				if($school->last_registration_year != null) {
					$presentation = Presentation::leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')
					->leftJoin('rotarians', 'presentations.rotarian_id', '=', 'rotarians.id')
					->selectRaw('presentations.id as id, presentations.created_at as created_at, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, date_format(presentations.requested_presentation_date, "%M %d, %Y %h:%i %p") as requested_presentation_date, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian, dentists.url_slug as dentist_url_slug, rotarians.url_slug as rotarian_url_slug, presentations.number_of_classes as total_classes, presentations.number_of_students as total_students, dentists.user_id as dentist_user, rotarians.user_id as rotarian_user, dentists.phone as dentist_phone, rotarians.phone as rotarian_phone, rotarians.rotary_club as rotary_club, presentations.is_paid as is_paid, dentists.contact_salutation as dentist_contact_salutation')
					->where('presentations.school_id', $school->id)->where('presentations.registration_year', $year)->first();
					$guests = Guest::where('presentation_id', $presentation->id)->get();
					$dentist_user = User::find($presentation->dentist_user);
					$rotarian_user = User::find($presentation->rotarian_user);
					$presentation->guests = $guests;
					$presentation->dentist_user = $dentist_user;
					$presentation->rotarian_user = $rotarian_user;
				} else {
					$presentation = null;
				}
				$school->presentation = $presentation;
			}
		} else {
			$schools = School::select('schools.id as id', 'schools.name as school_name', 'schools.url_slug as url_slug', 'users.email as email', 'schools.address as address', 'schools.city as city', 'schools.postal_code as postal_code', 'schools.province as province', 'schools.phone as phone', 'schools.requested_dentist as requested_dentist', 'schools.last_registration_year as last_registration_year')
				->selectRaw('CONCAT(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as contact')
				->leftJoin('users', 'schools.user_id', '=', 'users.id')
				->orderBy('schools.updated_at', 'desc')->get();
			foreach($schools as $school) {
				$school->school_slug = Str::slug($school->school_name.' '.$school->city, '+');
				if($school->last_registration_year != null) {
					$presentation = Presentation::leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')
					->leftJoin('rotarians', 'presentations.rotarian_id', '=', 'rotarians.id')
					->selectRaw('presentations.created_at as presentation_date, date_format(presentations.requested_presentation_date, "%M %d, %Y %h:%i %p") as requested_presentation_date, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, concat(rotarians.firstname, " ", rotarians.lastname) as rotarian, dentists.url_slug as dentist_url_slug, rotarians.url_slug as rotarian_url_slug, presentations.number_of_classes as total_classes, presentations.number_of_students as total_students, dentists.user_id as dentist_user, rotarians.user_id as rotarian_user, dentists.phone as dentist_phone, rotarians.phone as rotarian_phone, rotarians.rotary_club as rotary_club, presentations.is_paid as is_paid')
					->where('presentations.school_id', $school->id)->where('presentations.registration_year', $school->last_registration_year)->first();
					// $presentation = "Test";
					$dentist_user = User::find($presentation->dentist_user);
					$rotarian_user = User::find($presentation->rotarian_user);
					$presentation->dentist_user = $dentist_user;
					$presentation->rotarian_user = $rotarian_user;
				} else {
					$presentation = null;
				}
				$school->presentation = $presentation;
			}
		}

		return $schools;
	}

	function exportSchoolMasterList() {
		$schools = School::select('id', 'name', 'address', 'city', 'province', 'postal_code', 'phone')->get();
		return $schools;
	}
}