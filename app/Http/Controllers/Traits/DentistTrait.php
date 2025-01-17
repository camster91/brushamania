<?php 

namespace App\Http\Controllers\Traits;


use App\Dentist;
use App\DentistRegistration;
use App\Presentation;
use Illuminate\Support\Str;
use Auth;

trait DentistTrait
{
	function checkDentist($firstname, $lastname) {
		return (Dentist::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->count() >= 1);
	}

    function getDentist($firstname, $lastname) {
        $dentist = Dentist::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->first();
        if(!empty($dentist)) {
            return $dentist->id;
        }
        return 0;
    }

    function getDentistUser($id) {
        return Dentist::find($id)->user_id;
    }

    function addDentist($request, $user_id, $year) {
        $dentist = new Dentist;
        $dentist->user_id = $user_id;
        $dentist->last_registration_year = $year;
        $dentist->clinic_name = $request->clinic_name;
        $dentist->firstname = $request->dentist_firstname;
        $dentist->lastname = $request->dentist_lastname;
        $dentist->contact_salutation = $request->contact_salutation;
        $dentist->address = $request->address;
        $dentist->city = $request->city;
        // if ($request->province == 0 || $request->province == "0") {
        //     $dentist->province = null;
        // } else {
            $dentist->province = $request->province;
        // }
        $dentist->postal_code = $request->postal_code;
        $dentist->website = $request->website;
        $dentist->phone = $request->phone;
        // if(isset($dentist->postcard)) {
            $dentist->postcards = $request->postcard;
        // }
        if ($request->will_pick_up_local) {
            $dentist->dental_society_name = $request->dental_society_name;
        }

        $dentist->is_organizing_committee = $request->is_organizing_committee;
        $dentist->is_lootbag_committee = $request->is_lootbag_committee;
        $dentist->is_pr_media_committee = $request->is_pr_media_committee;
        $dentist->is_sponsor_committee = $request->is_sponsor_committee;
        $dentist->is_telephoning_committee = $request->is_telephoning_committee;
        $dentist->add_clinic_to_dentistfind = $request->add_clinic_to_dentistfind;
        $dentist->invite_token = $request->invite_token;

        $dentist->url_slug = Str::slug('dentist dr '.$request->dentist_firstname.' '.$request->dentist_lastname, '-');

        if ($dentist->save()) {
            return $dentist;
        }
        return null;
    }

    function updateDentist($request, $user_id, $dentist_id, $year = null) {
        $dentist = Dentist::find($dentist_id);
        $dentist->user_id = $user_id;
        if(isset($year)) {
            $dentist->last_registration_year = $year;
        }
        $dentist->clinic_name = $request->clinic_name;
        $dentist->firstname = $request->dentist_firstname;
        $dentist->lastname = $request->dentist_lastname;
        $dentist->contact_salutation = $request->contact_salutation;
        $dentist->address = $request->address;
        $dentist->city = $request->city;
        // if ($request->province == 0 || $request->province == "0") {
        //     $dentist->province = null;
        // } else {
            $dentist->province = $request->province;
        // }
        $dentist->postal_code = $request->postal_code;
        $dentist->website = $request->website;
        $dentist->phone = $request->phone;
        // if (isset($request->postcards)) {
            $dentist->postcards = $request->postcard;
        // }
        if ($request->will_pick_up_local) {
            $dentist->dental_society_name = $request->dental_society_name;
        } else {
            $dentist->dental_society_name = null;
            if (!isset($year)) {
                $dentist->dental_society_name = $request->dental_society_name;
            }
        }
        $dentist->is_organizing_committee = $request->is_organizing_committee;
        $dentist->is_lootbag_committee = $request->is_lootbag_committee;
        $dentist->is_pr_media_committee = $request->is_pr_media_committee;
        $dentist->is_sponsor_committee = $request->is_sponsor_committee;
        $dentist->is_telephoning_committee = $request->is_telephoning_committee;
        $dentist->add_clinic_to_dentistfind = $request->add_clinic_to_dentistfind;
        $dentist->invite_token = $request->invite_token;

        $dentist->url_slug = Str::slug('dentist dr '.$request->dentist_firstname.' '.$request->dentist_lastname, '-');

        if ($dentist->save()) {
            return $dentist;
        }
        return null;
    }

    function checkIfDentistIsRegistered($dentist_id, $active_year) {
        $dentist = Dentist::find($dentist_id);
        if(!empty($dentist)) {
            return ($dentist->last_registration_year == $active_year);
        }
        return 0;
    }

    function getDentistsList($year) {
        $dentists = DentistRegistration::rightJoin('dentists', 'dentists.id', 'dentist_registrations.dentist_id')
            ->leftJoin('users', 'dentists.user_id', '=', 'users.id')
            ->selectRaw('concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist_name, dentists.id as id, users.email as email, dentists.url_slug as url_slug')
            ->where('dentist_registrations.year', $year)
            ->orderBy('dentists.updated_at', 'desc')->get();
        foreach($dentists as $dentist) {
            $presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.name as school, schools.url_slug as url_slug')->where('presentations.dentist_id', $dentist->id)->where('presentations.registration_year', $year)->whereNull('schools.invite_token')->orderBy('presentations.presentation_date', 'desc')->get();
            $dentist->presentations = $presentations;
        }
        return $dentists;
    }

    function showDentist($dentist) {
        $dentist = Dentist::leftJoin('users', 'dentists.user_id', '=', 'users.id')
            ->selectRaw('concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist_name, dentists.clinic_name as clinic, concat(dentists.contact_salutation, " ", users.firstname, " ", users.lastname) as contact, dentists.address as address, dentists.city as city, dentists.province as province, dentists.postal_code as postal_code, dentists.website as website, dentists.phone as phone')
            ->whereRaw("lower(dentists.url_slug) = '".strtolower($dentist)."'")->first();
        return $dentist;
    }

    function editDentist($dentist) {
        $dentist = Dentist::leftJoin('users', 'dentists.user_id', '=', 'users.id')
            ->select('dentists.id as id','dentists.clinic_name as clinic_name', 'dentists.firstname as dentist_firstname', 'dentists.lastname as dentist_lastname', 'users.email as email', 'dentists.contact_salutation as contact_salutation', 'users.firstname as contact_firstname', 'users.lastname as contact_lastname', 'dentists.address as address', 'dentists.city as city', 'dentists.province as province', 'dentists.postal_code as postal_code', 'dentists.phone as phone', 'dentists.website as website', 'dentists.add_clinic_to_dentistfind as add_clinic_to_dentistfind', 'dentists.is_organizing_committee as is_organizing_committee', 'dentists.is_lootbag_committee as is_lootbag_committee', 'dentists.is_pr_media_committee as is_pr_media_committee', 'dentists.is_sponsor_committee as is_sponsor_committee', 'dentists.is_telephoning_committee as is_telephoning_committee', 'dentists.postcards as postcard', 'dentists.dental_society_name as dental_society_name')
            ->whereRaw("lower(url_slug) = '".strtolower($dentist)."'")->first();
        if(!isset($dentist->province)) {
            $dentist->province = 0;
        }
        return $dentist;
    }

    function deleteDentist($id) {
        return Dentist::find($id)->delete();
    }

    function getRegisteredDentists($year) {
        $dentists = Dentist::selectRaw('id, concat("Dr. ", firstname, " ", lastname) as name')
            ->where('last_registration_year', $year)->get();
        return $dentists;
    }

    function getRegisteredDentistsCount($year) {
        return Dentist::where('last_registration_year', $year)->count();
    }

    function getDentistRequest($invite_token) {
        $dentist = Dentist::whereRaw("lower(invite_token) = '".strtolower($invite_token)."'")->first();
        if(!empty($dentist)) {
            return $dentist;
        }
        return null;
    }

    function addDentistRegistration($id, $year) {
        $registration = new DentistRegistration;
        $registration->dentist_id = $id;
        $registration->year = $year;
        return $registration->save();
    }

    function exportDentists($year) {
        $dentists = DentistRegistration::rightJoin('dentists', 'dentists.id', 'dentist_registrations.dentist_id')
            ->leftJoin('users', 'dentists.user_id', '=', 'users.id')
            ->select('dentists.id as id', 'dentists.phone as phone', 'dentists.address as address', 'dentists.city as city', 'dentists.province as province', 'dentists.postal_code as postal_code', 'dentists.clinic_name as clinic_name', 'dentists.website as website', 'dentists.postcards as postcards', 'dentists.dental_society_name as dental_society_name', 'dentists.is_organizing_committee as is_organizing_committee', 'dentists.is_lootbag_committee as is_lootbag_committee', 'dentists.is_pr_media_committee as is_pr_media_committee', 'dentists.is_sponsor_committee as is_sponsor_committee', 'dentists.is_telephoning_committee as is_telephoning_committee', 'dentists.add_clinic_to_dentistfind as add_clinic_to_dentistfind')
            ->selectRaw('concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist_name, users.email as email, concat(dentists.contact_salutation, " ", users.firstname, " ", users.lastname) as dentist_contact_name')
            ->where('dentist_registrations.year', $year)
            ->orderBy('dentists.updated_at', 'desc')->get();
        foreach($dentists as $dentist) {
            $presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->selectRaw('date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.name as school')->where('presentations.dentist_id', $dentist->id)->where('presentations.registration_year', $year)->whereNull('schools.invite_token')->orderBy('presentations.presentation_date', 'desc')->get();
            $dentist->presentations = $presentations;
        }
        return $dentists;
    }

}