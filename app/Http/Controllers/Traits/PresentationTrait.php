<?php

namespace App\Http\Controllers\Traits;


use App\Presentation;
use Illuminate\Support\Facades\Mail;
use App\Mail\DentistConfirmedForSchoolMail;
use App\Mail\SchoolConfirmedForDentistMail;
use App\Dentist;
use App\School;
use App\User;
use App\Guest;
use Auth;

trait PresentationTrait
{
	function checkPresentationSchool($year, $school_id) {
		return Presentation::where('registration_year', $year)->where('school_id', $school_id)->count();
	}

    function checkPresentationDentist($year, $dentist_id) {
        return Presentation::where('registration_year', $year)->where('dentist_id', $dentist_id)->count();
    }

    function getSchoolPresentation($year, $school_id) {
        return Presentation::where('registration_year', $year)->where('school_id', $school_id)->first();
    }

	function addPresentation($request, $active_year, $school_id = null, $dentist_id = null, $rotarian_id = null) {
		$presentation = new Presentation();
        $presentation->school_id = $school_id;
        $presentation->dentist_id = $dentist_id;
        $presentation->rotarian_id = $rotarian_id;
        $presentation->number_of_classes = $request->number_of_classes;
        $presentation->number_of_students = $request->number_of_students;
        $presentation->registration_year = $active_year;
        if(isset($request->presentation_date) || $request->presentation_date == null || $request->presentation_date == "") {
            $presentation->requested_presentation_date = $request->presentation_date;
        }
        if($presentation->save()) {
            if (!empty($presentation->dentist_id) && $request->dentist_id != null && $request->dentist_id != "") {
                $this->sendConfirmationMail($presentation->dentist_id, $presentation->school_id);
            }
        	return $presentation;
        }
        return null;
	}

	function updatePresentation($request, $id) {
        $presentation = Presentation::find($id);
        $old_dentist = $presentation->dentist_id;
        $presentation->dentist_id = $request->dentist_id;
        $presentation->rotarian_id = $request->rotarian_id;
        $presentation->number_of_classes = $request->number_of_classes;
        $presentation->number_of_students = $request->number_of_students;
        $presentation->presentation_date = $request->presentation_date;
        $presentation->requested_presentation_date = $request->requested_presentation_date;
        $presentation->is_paid = $request->is_paid;
        if($presentation->save()) {
            if(!empty($request->guests[0])) {
                $this->addGuests($request->guests, $presentation->id);
            }
            if (empty($old_dentist_id) || $old_dentist == null || $old_dentist == "") {
                if (!empty($presentation->dentist_id) && $request->dentist_id != null && $request->dentist_id != "") {
                    $this->sendConfirmationMail($presentation->dentist_id, $presentation->school_id);
                }
            } else if ($old_dentist != $presentation->dentist_id) {
               $this->sendConfirmationMail($presentation->dentist_id, $presentation->school_id);
            }
            return true;
        }
        return false;
    }

    function addGuests($guests, $presentation_id)
    {
        Guest::where('presentation_id', $presentation_id)->delete();
        foreach($guests as $guest) {
            $new_guest = new Guest;
            $new_guest->presentation_id = $presentation_id;
            $new_guest->name = $guest;
            $new_guest->save();
        }
    }

    function assignDentist($id, $dentist_id) {
        $presentation = Presentation::find($id);
        $presentation->dentist_id = $dentist_id;
        if($presentation->save()) {
            $this->sendConfirmationMail($presentation->dentist_id, $presentation->school_id);
        }
    }

    function assignRotarian($id, $rotarian_id) {
        $presentation = Presentation::find($id);
        $presentation->rotarian_id = $rotarian_id;
        return $presentation->save();
    }

    function sendConfirmationMail($dentist_id, $school_id) {
        $dentist = Dentist::find($dentist_id);
        $school = School::find($school_id);
        $dentist_user = User::find($dentist->user_id);
        $school_user = User::find($school->user_id);
        if(config('app.env') == 'local') {
            $cc1 = [
                ['email' => 'webdev@dentistfind.com'],
                ['email' => 'abbasumaru44@gmail.com']
            ];
            $cc2 = [
                ['email' => 'webdev@dentistfind.com'],
                ['email' => 'abbasumaru44@gmail.com']
            ];
            Mail::to($school_user->email)->bcc($cc1)->queue(new DentistConfirmedForSchoolMail($school->name, $dentist, $dentist_user));
            Mail::to($dentist_user->email)->bcc($cc2)->queue(new SchoolConfirmedForDentistMail($dentist, $school, $school_user));
        } else {
            $cc1 = [
                ['email' => 'jennifer.boyd@hotmail.com'],
				['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
				['email' => 'abbasumaru44@gmail.com']
            ];
            $cc2 = [
                ['email' => 'jennifer.boyd@hotmail.com'],
				['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
				['email' => 'abbasumaru44@gmail.com']
            ];
            Mail::to($school_user->email)->bcc($cc1)->queue(new DentistConfirmedForSchoolMail($school->name, $dentist, $dentist_user));
            Mail::to($dentist_user->email)->bcc($cc2)->queue(new SchoolConfirmedForDentistMail($dentist, $school, $school_user));
        }
    }
    function deletePresentations($school) {
        return Presentation::where('school_id', $school)->delete();
    }

    function removeDentistFromPresentations($dentist) {
        return Presentation::where('dentist_id', $dentist)->update([
            'dentist_id' => null
        ]);
    }

    function removeRotarianFromPresentations($rotarian) {
        return Presentation::where('rotarian_id', $rotarian)->update([
            'rotarian_id' => null
        ]);
    }

    // function getPresentationWithNoAssigned($assigned, $year, $postal_code) {
    //     $presentations = [];
    //     if (empty($postal_code) || $postal_code == null) {
    //         $presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->select('schools.name as name', 'presentations.id as id')->where('registration_year', $year)->whereNull($assigned)->orderBy('name', 'asc')->get();
    //     } else {
    //         $presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->select('schools.name as name', 'presentations.id as id')->where('registration_year', $year)->where('schools.postal_code', $postal_code)->whereNull($assigned)->orderBy('name', 'asc')->get();
    //     }

    //     return $presentations;
    // }

    function getPresentationWithNoAssigned($assigned, $year) {
        $presentations = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->select('schools.name as name', 'presentations.id as id', 'schools.postal_code as postal_code')->where('registration_year', $year)->whereNotNull('schools.postal_code')->whereNull($assigned)->orderBy('name', 'asc')->get();
        return $presentations;
    }

    function showPresentation($school, $year) {
        $presentation = Presentation::leftJoin('schools', 'presentations.school_id', '=', 'schools.id')
        ->select('schools.name as school_name', 'presentations.id as id', 'presentations.dentist_id as dentist_id', 'presentations.rotarian_id as rotarian_id', 'presentations.number_of_classes as number_of_classes', 'presentations.number_of_students as number_of_students', 'presentations.is_paid as is_paid')
        ->selectRaw('date_format(presentations.presentation_date, "%Y-%m-%dT%H:%i") as presentation_date, date_format(presentations.requested_presentation_date, "%Y-%m-%dT%H:%i") as requested_presentation_date')
        ->where('schools.url_slug', $school)
        ->where('presentations.registration_year', $year)
        ->first();
        $guests = Guest::where('presentation_id', $presentation->id)->get();
        $presentation->guests = $guests->map(function($guest) {
            return $guest->name;
        });
        return $presentation;
    }

    function getYearPresentationList($year) {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;
            if($role == 'admin') {
                $presentations = Presentation::selectRaw('schools.name as school, schools.url_slug as school_url_slug, dentists.url_slug as dentist_url_slug, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.postal_code as postal_code, presentations.is_paid as is_paid')->leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')->where('presentations.registration_year', $year)->orderBy('schools.name', 'asc')->get();
            } else if($role == 'dentist') {
                $dentist = $user->dentist;
                $presentations = Presentation::selectRaw('schools.name as school, schools.url_slug as school_url_slug, concat(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as contact, users.email as email, schools.phone as phone, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date')->leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->leftJoin('users', 'schools.user_id', 'users.id')->where('presentations.registration_year', $year)->where('presentations.dentist_id', $dentist->id)->orderBy('presentations.presentation_date', 'asc')->get();
            } else if($role == 'rotarian') {
                $rotarian = $user->rotarian;
                $presentations = Presentation::selectRaw('schools.name as school, schools.url_slug as school_url_slug, concat(schools.contact_salutation, " ", users.firstname, " ", users.lastname) as contact, users.email as email, schools.phone as phone, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date')->leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->leftJoin('users', 'schools.user_id', 'users.id')->where('presentations.registration_year', $year)->where('presentations.rotarian_id', $rotarian->id)->orderBy('presentations.presentation_date', 'asc')->get();
            }
        } else {
            $presentations = Presentation::selectRaw('schools.name as school, schools.url_slug as school_url_slug, schools.city as school_city, dentists.url_slug as dentist_url_slug, concat("Dr. ", dentists.firstname, " ", dentists.lastname) as dentist, date_format(presentations.presentation_date, "%M %d, %Y %h:%i %p") as presentation_date, schools.postal_code as postal_code, presentations.is_paid as is_paid')->leftJoin('schools', 'presentations.school_id', '=', 'schools.id')->leftJoin('dentists', 'presentations.dentist_id', '=', 'dentists.id')->where('presentations.registration_year', $year)->orderBy('schools.name', 'asc')->get();
        }


        return $presentations;
    }
}
