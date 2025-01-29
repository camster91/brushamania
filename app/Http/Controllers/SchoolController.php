<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use DB;
use App\School;
use App\User;
use App\Presentation;
use App\Http\Controllers\Traits\UserTrait;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\SchoolTrait;
use App\Http\Controllers\Traits\PresentationTrait;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolRegistrationMail;
use Excel;
use Illuminate\Support\Str;
use App\Exports\SchoolsExport;
use App\Exports\NewSchoolExport;
use App\Exports\SchoolsMasterListExport;
use App\Imports\SchoolsMasterListImport;

class SchoolController extends Controller
{
    use UserTrait;
    use YearTrait;
    use SchoolTrait;
    use PresentationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('registration.school', [
            'page_title' => 'School Registration'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolRequest $request)
    {
        // if(config('app.env') == 'staging') {
        //     $rules = [
        //         'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('school_registration')]
        //     ];
        //     $input = [
        //         'gRecaptchaResponse' => $request->gRecaptchaResponse
        //     ];
        //     if (!Validator::make($input, $rules)->passes()) {
        //         return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        //     }
        // }
        $year = $this->getActiveYear();
        if (isset($request->selected_year)) {
            $year = $request->selected_year;
        }

        if ($this->checkClosureDate(false)) {
            $year = now()->addYear()->year;
        }

        $user_added = false;

        if (!empty($request->old_invite_token) && $request->old_invite_token != null && $request->old_invite_token != "") {
            $school_get = $this->getSchoolRequest($request->old_invite_token);
            if (empty($school_get->user_id)) {
                $email_count = User::where('email', $request->email)->count();
                if($email_count > 0) {
                    return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
                }
                $user = $this->addUser($request, 'school');
                $user_added = true;
            } else {
                $email_count = User::where('email', $request->email)->where('id', '!=', $school_get->user_id)->count();
                if($email_count > 0) {
                    return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
                }
                $user = $this->updateUser($request, $school_get->user_id, 'school');
            }
            $school = $this->updateSchool($request, $user->id, $school_get->id, $year);

            if ($this->addSchoolRegistration($school->id, $year)) {
                $presentation = $this->getSchoolPresentation($year, $school->id);
                $presentation->number_of_classes = $request->number_of_classes;
                $presentation->number_of_students = $request->number_of_students;
                if(isset($request->presentation_date) || $request->presentation_date == null || $request->presentation_date == "") {
                    $presentation->requested_presentation_date = $request->presentation_date;
                }
                if ($presentation->save()) {
                    if (!isset($request->selected_year)) {
                        $this->verifyEnvironmentAndSendEmail($school, $user, $presentation, $user_added, $request);
                    }
                    return response()->json(['success' => true, 'msg' => 'School Registered Successfully'], 200);
                }
            }
            return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        }

        $school_id = $this->getSchool($request->name, $request->city);
        // return $school_id;
        if($this->checkIfSchoolIsRegistered($school_id, $year)) {
            return response()->json(['success' => false, 'msg' => 'School Already Registered'], 400);
        }
        if($this->checkPresentationSchool($year, $school_id)) {
            return response()->json(['success' => false, 'msg' => 'School Already Registered'], 400);
        }

        if ($school_id > 0) {
            $school_find = School::find($school_id);
            $email_count = User::where('email', $request->email)->where('id', '!=', $school_find->user_id)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            if(!empty(User::find($school_find->user_id))) {
                $user = $this->updateUser($request, $school_find->user_id, 'school');
            } else {
                $user = $this->addUser($request, 'school');
                $user_added = true;
            }

        } else {
            $email_count = User::where('email', $request->email)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->addUser($request, 'school');
            $user_added = true;
        }

        if(!empty($user)) {
            if($this->checkSchool($request->name, $request->city)) {
                $school = $this->updateSchool($request, $user->id, $school_id, $year);
            } else {
                $school = $this->addSchool($request, $user->id, $year);
            }
            if(!empty($school)) {
                if ($this->addSchoolRegistration($school->id, $year)) {
                    $presentation = $this->addPresentation($request, $year, $school->id);
                    if(!empty($presentation)) {
                        $this->verifyEnvironmentAndSendEmail($school, $user, $presentation, $user_added, $request);

                        return response()->json(['success' => true, 'msg' => 'School Registered Successfully'], 200);
                    } else {

                        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
                    }
                }
                return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
            }
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($school, $year = null)
    {
        if(!isset($year)) {
            $year = $this->getActiveYear();
        }
        // return $year;
        $school = $this->showSchool($school, $year);
        return response()->json(['school' => $school], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($school)
    {
        $year = $this->getActiveYear();
        $school = $this->editSchool($school, $year);
        return response()->json(['school' => $school], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSchoolRequest $request, $id)
    {
        $school = School::find($id);
        $email_count = User::where('email', $request->email)->where('id', '!=', $school->user_id)->count();
        if($email_count > 0) {
            return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
        }
        $user = $this->updateUser($request, $school->user_id, 'school');
        if(!empty($user)) {
            $school = $this->updateSchool($request, $user->id, $id);
            if(!empty($school)) {
                return response()->json(['success' => true, 'msg' => 'School Updated Successfully', 'url_slug' => $school->url_slug], 200);

            }
            return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);

        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->getSchoolUser($id);
        if($this->deleteSchool($id)) {
            $this->deletePresentations($id);
            $this->deleteUser($user);
            return response()->json(['success' => true, 'msg' => 'School Deleted Successfully']);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    public function storeUnregistered(Request $request) {
        if($this->checkSchool($request->name, $request->city)) {
            return response()->json(['success' => false, 'msg' => 'School is already in database'], 400);
        } else {
            $school = $this->addSchool($request, null, null);
        }
    }

    public function getList($year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $schools = $this->getSchoolList($year);
        return response()->json(['schools' => $schools], 200);
    }

    public function publicView($school)
    {
        $year = $this->getActiveYear();
        $school = $this->showSchool($school, $year);
        return view('public_viewing.school', [
            'page_title' => $school->school_name,
            'school' => $school
        ]);
    }

    public function checkClosureDate($asJsonResponse = true)
    {
        $date = now()->endOfDay(); 
        
        $closureDate = CarbonImmutable::createFromFormat('d-m-Y', '08-03-2025')->endOfDay();
        //$closureDate = CarbonImmutable::createFromFormat('d-m-Y', '05-03-2024')->endOfDay();
        
        $status = $date->greaterThanOrEqualTo($closureDate);

        return $asJsonResponse
            ? response()->json(['status' => $status], 200)
            : $status;
    }

    public function export($year) {
        // return Excel::download(new SchoolsExport($year), 'schools.xlsx');
        return Excel::download(new NewSchoolExport($year), 'schools.xlsx');
    }

    public function autofill($city, $school) {
        $schooldata = $this->autofillSchool($city, $school);
        return response()->json(['school' => $schooldata], 200);
    }

    public function listAll() {
        return response()->json(['schools' => $this->listAllSchools()], 200);
    }

    public function exportSchool($year)
    {
        $schools = $this->exportSchools($year);
        return response()->json([
            'schools' => $schools
        ], 200);
    }

    public function exportMasterList()
    {
        return Excel::download(new SchoolsMasterListExport(), 'schools master list.xlsx');
    }

    public function importMasterList(Request $request)
    {
        Excel::import(new SchoolsMasterListImport, $request->file('file'));
        return response()->json(['success' => true, 'msg' => 'Schools Are Being Imported','file' => $request->hasFile('file')], 200);
    }

    public function verifyEnvironmentAndSendEmail($school, ?User $user, $presentation, bool $user_added, $request): void
    {
        if (app()->environment('local')) {
            $cc = [];

            collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                $cc[] = ['email' => $email];
            });


            Mail::to('dev@example.test')->bcc($cc)->send(new SchoolRegistrationMail($school, $user, $presentation, $user_added));
        } else {
            $cc = [];

            collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                $cc[] = ['email' => $email];
            });

            Mail::to($request->email)->bcc($cc)->queue(new SchoolRegistrationMail($school, $user, $presentation, $user_added));
        }
    }
}
