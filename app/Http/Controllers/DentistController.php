<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dentist;
use App\Http\Controllers\Traits\UserTrait;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\SchoolTrait;
use App\Http\Controllers\Traits\PresentationTrait;
use App\Http\Controllers\Traits\DentistTrait;
use Illuminate\Support\Facades\Validator;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
use App\Http\Requests\StoreDentistRequest;
use App\Http\Requests\UpdateDentistRequest;
use App\School;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DentistRegistrationMail;
use App\Mail\SchoolRegistrationMail;
use App\Mail\SchoolInviteEmail;
use Illuminate\Support\Str;
use Excel;
use App\Exports\DentistsExport;
use App\Imports\DentistsImport;

class DentistController extends Controller
{
    use UserTrait;
    use YearTrait;
    use SchoolTrait;
    use PresentationTrait;
    use DentistTrait;

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
        return view('registration.dentist', [
            'page_title' => 'Dentist Registration'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDentistRequest $request)
    {
        // if(config('app.env') == 'staging') {
        //     $rules = [
        //         'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('dentist_registration')]
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

        $user_added = false;


        if (!empty($request->old_invite_token) && $request->old_invite_token != null && $request->old_invite_token != "") {
            $dentist_get = $this->getDentistRequest($request->old_invite_token);
            if ($dentist_get->user_id == null || empty($dentist_get->user_id)) {
                $email_count = User::where('email', $request->email)->count();
                if($email_count > 0) {
                    return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
                }
                $user = $this->addUser($request, 'dentist');
                $user_added = true;
            } else {
                $email_count = User::where('email', $request->email)->where('id', '!=', $dentist_get->user_id)->count();
                if($email_count > 0) {
                    return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
                }
                $user = $this->updateUser($request, $dentist_get->user_id, 'dentist');
            }
            $dentist = $this->updateDentist($request, $user->id, $dentist_get->id, $year);

            if (!empty($dentist)) {
                if (app()->environment('local')) {
                    $cc = [];

                    collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                        $cc[] = ['email' => $email];
                    });


                    Mail::to('dev@example.test')->bcc($cc)->send(new DentistRegistrationMail($dentist, "", $year, $user, $user_added));
                } else {
                    $cc = [];

                    collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                        $cc[] = ['email' => $email];
                    });
                    Mail::to($request->email)->bcc($cc)->queue(new DentistRegistrationMail($dentist, "", $year, $user, $user_added));
                }
                return response()->json(['success' => true, 'msg' => 'Dentist Registered Successfully'], 200);
            }
            return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        }




        $dentist_id = $this->getDentist($request->dentist_firstname, $request->dentist_lastname);

        if($this->checkIfDentistIsRegistered($dentist_id, $year)) {
            return response()->json(['success' => false, 'msg' => 'Dentist Already Registered'], 400);
        }
        if($this->checkPresentationDentist($year, $dentist_id)) {
            return response()->json(['success' => false, 'msg' => 'Dentist Already Registered'], 400);
        }

        if ($dentist_id > 0) {
            $dentist_find = Dentist::find($dentist_id);
            $email_count = User::where('email', $request->email)->where('id', '!=', $dentist_find->user_id)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->updateUser($request, $dentist_find->user_id, 'dentist');
        } else {
            $email_count = User::where('email', $request->email)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->addUser($request, 'dentist');
            $user_added = true;
        }

        if(!empty($user)) {
            if($this->checkDentist($request->dentist_firstname, $request->dentist_lastname)) {
                $dentist = $this->updateDentist($request, $user->id, $dentist_id, $year);
            } else {
                $dentist = $this->addDentist($request, $user->id, $year);
            }
            $this->addDentistRegistration($dentist->id, $year);
            if(!empty($dentist) && $request->will_enter_school == 1) {
                $school_id = $this->getSchool($request->school_name);
                if($this->checkIfSchoolIsRegistered($school_id, $year)) {
                    return response()->json(['success' => false, 'msg' => 'School Already Registered'], 400);
                }
                if($this->checkPresentationSchool($year, $school_id)) {
                    return response()->json(['success' => false, 'msg' => 'School Already Registered'], 400);
                }
                $req = new \stdClass();
                $req->contact_firstname = $request->school_contact_firstname;
                $req->contact_lastname = $request->school_contact_lastname;
                $req->email = $request->school_email;

                $invite_token = Str::random(10);
                $school = new School;
                if ($school_id > 0) {
                    $school = School::find($school_id);
                    $school->invite_token = $invite_token;
                    $school->save();
                    $presentation = $this->addPresentation($request, $year, $school->id);
                } else {
                    $school_req = new \stdClass();
                    $school_req->name = $request->school_name;
                    $school_req->address = null;
                    $school_req->city = null;
                    $school_req->province = null;
                    $school_req->postal_code = null;
                    $school_req->phone = null;
                    $school_req->contact_salutation =  'Ms.';
                    $school_req->requested_dentist = null;
                    $school_req->invite_token = $invite_token;
                    $school_req->user_id = null;
                    $school = $this->addSchool($school_req, null, $year);
                    $presentation = $this->addPresentation($request, $year, $school->id);
                }
                if (isset($request->selected_year)) {
                    if (app()->environment('local')) {
                        $cc = [];

                        collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });

                        Mail::to('dev@example.test')->bcc($cc)->send(new DentistRegistrationMail($dentist, $school->name, $year, $user, $user_added));
                        Mail::to('dev@example.test')->bcc($cc)->send(new SchoolInviteEmail($school->name, $dentist, $invite_token, $year));
                    } else {
                        $cc = [];

                        collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });
                        Mail::to($request->email)->bcc($cc)->queue(new DentistRegistrationMail($dentist, $school->name, $year, $user, $user_added));
                        Mail::to($request->school_email)->bcc($cc)->queue(new SchoolInviteEmail($school->name, $dentist, $invite_token, $year));
                    }
                }
            } else if (!empty($dentist) && $request->will_enter_school == 2) {
                foreach ($request->selected_schools as $selected_school) {
                    $this->assignDentist($selected_school, $dentist->id);
                }
            }
            if (isset($request->selected_year)) {
                if (app()->environment('local')) {
                    $cc = [];

                    collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                        $cc[] = ['email' => $email];
                    });

                    Mail::to('dev@example.test')->bcc($cc)->send(new DentistRegistrationMail($dentist, "", $year, $user, $user_added));
                } else {
                    $cc = [];

                    collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                        $cc[] = ['email' => $email];
                    });

                    Mail::to($request->email)->bcc($cc)->queue(new DentistRegistrationMail($dentist, "", $year, $user, $user_added));
                }
            }
            return response()->json(['success' => true, 'msg' => 'Dentist Registered Successfully'], 200);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        // return $request->postcard;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dentist)
    {
        $dentist = $this->showDentist($dentist);
        return response()->json(['dentist' => $dentist], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($dentist)
    {
        $dentist = $this->editDentist($dentist);
        return response()->json(['dentist' => $dentist], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDentistRequest $request, $id)
    {
        $dentist = Dentist::find($id);
        $email_count = User::where('email', $request->email)->where('id', '!=', $dentist->user_id)->count();
        if($email_count > 0) {
            return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
        }
        $user = $this->updateUser($request, $dentist->user_id, 'dentist');
        if(!empty($user)) {
            $dentist = $this->updateDentist($request, $user->id, $id);
            if(!empty($dentist)) {
                return response()->json(['success' => true, 'msg' => 'Dentist Updated Successfully', 'url_slug' => $dentist->url_slug], 200);
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
        $user = $this->getDentistUser($id);
        if($this->deleteDentist($id)) {
            $this->removeDentistFromPresentations($id);
            $this->deleteUser($user);
            return response()->json(['success' => true, 'msg' => 'Dentist Deleted Successfully']);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    public function getList($year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $dentists = $this->getDentistsList($year);
        return response()->json(['dentists' => $dentists], 200);
    }

    public function getRegistered()
    {
        $year = $this->getActiveYear();
        $dentists = $this->getRegisteredDentists($year);
        return response()->json(['dentists' => $dentists], 200);
    }

    public function publicView($dentist)
    {
        $year = $this->getActiveYear();
        $dentist = $this->showDentist($dentist, $year);
        return view('public_viewing.dentist', [
            'page_title' => $dentist->dentist_name,
            'dentist' => $dentist
        ]);
    }

    public function export($year) {
        return Excel::download(new DentistsExport($year), 'dentists.xlsx');
        // return response()->json(['dentists' => $this->exportDentists($year)], 200);
    }

    public function import(Request $request)
    {
        Excel::import(new DentistsImport, $request->file('file'));
        return response()->json(['success' => true, 'msg' => 'Dentists Are Being Imported','file' => $request->hasFile('file')], 200);
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
