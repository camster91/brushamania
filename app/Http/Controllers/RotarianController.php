<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rotarian;
use App\User;
use App\Http\Controllers\Traits\UserTrait;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\RotarianTrait;
use App\Http\Controllers\Traits\PresentationTrait;
use App\Http\Controllers\Traits\SchoolTrait;
use App\Http\Controllers\Traits\DentistTrait;
use Illuminate\Support\Facades\Validator;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
use App\Http\Requests\StoreRotarianRequest;
use App\Http\Requests\UpdateRotarianRequest;
use Excel;
use Illuminate\Support\Facades\Mail;
use App\Exports\RotariansExport;
use App\Mail\RotarianSchoolInvitationMail;
use App\Mail\RotarianDentistInvitationMail;
use App\Mail\RotarianRegistrationMail;
use Illuminate\Support\Str;

class RotarianController extends Controller
{
    use UserTrait;
    use YearTrait;
    use RotarianTrait;
    use PresentationTrait;
    use DentistTrait;
    use SchoolTrait;

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
        return view('registration.rotarian', [
            'page_title' => 'Rotarian Registration'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRotarianRequest $request)
    {
        // if(config('app.env') == 'staging') {
        //     $rules = [
        //         'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('rotarian_registration')]
        //     ];
        //     $input = [
        //         'gRecaptchaResponse' => $request->gRecaptchaResponse
        //     ];
        //     if (!Validator::make($input, $rules)->passes()) {
        //         return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        //     }
        // }
        $user_added = false;
        $year = $this->getActiveYear();
        if (isset($request->selected_year)) {
            $year = $request->selected_year;
        }
        $rotarian_id = $this->getRotarian($request->contact_firstname, $request->contact_lastname);
        if($this->checkIfRotarianIsRegistered($rotarian_id, $year)) {
            return response()->json(['success' => false, 'msg' => 'Rotarian Already Registered'], 400);
        }

        if ($rotarian_id > 0) {
            $rotarian_find = Rotarian::find($rotarian_id);
            $email_count = User::where('email', $request->email)->where('id', '!=', $rotarian_find->user_id)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->updateUser($request, $rotarian_find->user_id, 'rotarian');
        } else {
            $email_count = User::where('email', $request->email)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->addUser($request, 'rotarian');
            $user_added = true;
        }

        if (!empty($user)) {
            if ($this->checkRotarian($request->contact_firstname, $request->contact_lastname)) {
                $rotarian = $this->updateRotarian($request, $user->id, $rotarian_id, $year);
            } else {
                $rotarian = $this->addRotarian($request, $user->id, $year);
            }
            if(!empty($rotarian)) {
                if ($this->addRotarianRegistration($rotarian->id, $year)) {
                    if ($request->will_enter_school == 1) {
                        $invite_token = Str::random(10);
                        $dentist_id = $this->getDentist($request->dentist_firstname, $request->dentist_lastname);
                        $dentist_new = null;
                        if(!$this->checkIfDentistIsRegistered($dentist_id, $year)) {
                            if ($dentist_id > 0) {
                                $dentist_find = Dentist::find($dentist_id);
                                $dentist_find->invite_token = $invite_token;
                                $dentist_find->save();
                                $dentist_new = $dentist_find;
                            } else {
                                $dentist_req = new \stdClass();
                                $dentist_req->clinic_name = null;
                                $dentist_req->dentist_firstname = $request->dentist_firstname;
                                $dentist_req->dentist_lastname = $request->dentist_lastname;
                                $dentist_req->contact_salutation = 'Dr.';
                                $dentist_req->address = null;
                                $dentist_req->city = null;
                                $dentist_req->province = 0;
                                $dentist_req->postal_code = null;
                                $dentist_req->website = null;
                                $dentist_req->phone = $request->dentist_phone;
                                $dentist_req->postcards = 0;
                                $dentist_req->will_pick_up_local = false;
                                $dentist_req->dental_society_name = null;
                                $dentist_req->is_organizing_committee = false;
                                $dentist_req->is_lootbag_committee = false;
                                $dentist_req->is_pr_media_committee = false;
                                $dentist_req->is_sponsor_committee = false;
                                $dentist_req->is_telephoning_committee = false;
                                $dentist_req->add_clinic_to_dentistfind = false;
                                $dentist_req->invite_token = $invite_token;
                                $dentist = $this->addDentist($dentist_req, null, $year);
                                $dentist_id = $dentist->id;
                                $dentist_new = $dentist;
                            }

                        }




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


                        if ($school_id > 0) {
                            $school_find = School::find($school_id);
                            $school_find->invite_token = $invite_token;
                            $school_find->save();
                            $presentation = $this->addPresentation($request, $year, $school_find->id, $dentist_id, $rotarian->id);
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
                            $presentation = $this->addPresentation($request, $year, $school->id, $dentist_id, $rotarian->id);
                        }
                        if (!isset($request->selected_year)) {
                            if (app()->environment('local')) {
                                $cc = [];

                                collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                                    $cc[] = ['email' => $email];
                                });

                                // Mail::to('earl@dentistrybusiness.com')->bcc($cc)->send(new DentistRegistrationMail($dentist, "", $year, $user, $user_added));
                                Mail::to('dev@example.test')->bcc($cc)->send(new RotarianSchoolInvitationMail($request->school_name, $rotarian, $invite_token, $year));
                                Mail::to('dev@example.test')->bcc($cc)->send(new RotarianDentistInvitationMail($dentist, $rotarian, $invite_token, $year));
                            } else {
                                $cc = [];

                                collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                                    $cc[] = ['email' => $email];
                                });

                                Mail::to($request->school_email)->bcc($cc)->send(new RotarianSchoolInvitationMail($request->school_name, $rotarian, $invite_token, $year));
                                Mail::to($request->dentist_email)->bcc($cc)->send(new RotarianDentistInvitationMail($dentist, $rotarian, $invite_token, $year));
                            }
                        }

                    }
                    else if ($request->will_enter_school == 2) {
                        foreach ($request->selected_schools as $selected_school) {
                            $this->assignRotarian($selected_school, $rotarian->id);
                        }
                    }
                    if (app()->environment('local')) {
                        $cc = [];

                        collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });

                        Mail::to('dev@example.test')->bcc($cc)->send(new RotarianRegistrationMail($rotarian, $year, $user, $user_added));
                    } else {
                        $cc = [];

                        collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });

                        Mail::to($request->email)->bcc($cc)->queue(new RotarianRegistrationMail($rotarian, $year, $user, $user_added));
                    }
                    return response()->json(['success' => true, 'msg' => 'Rotarian Registered Successfully'], 200);
                }
                return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
            }
            return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($rotarian)
    {
        $rotarian = $this->showRotarian($rotarian);
        return response()->json(['rotarian' => $rotarian], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($rotarian)
    {
        $rotarian = $this->editRotarian($rotarian);
        return response()->json(['rotarian' => $rotarian], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRotarianRequest $request, $id)
    {
        $rotarian = Rotarian::find($id);
        $email_count = User::where('email', $request->email)->where('id', '!=', $rotarian->user_id)->count();
        if($email_count > 0) {
            return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
        }
        $user = $this->updateUser($request, $rotarian->user_id, 'rotarian');
        if (!empty($user)) {
            $rotarian = $this->updateRotarian($request, $user->id, $id);
            if (!empty($rotarian)) {
                return response()->json(['success' => true, 'msg' => 'Rotarian Updated Successfully', 'url_slug' => $rotarian->url_slug], 200);
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
        $user = $this->getRotarianUser($id);
        if($this->deleteRotarian($id)) {
            $this->removeRotarianFromPresentations($id);
            $this->deleteUser($user);
            return response()->json(['success' => true, 'msg' => 'Rotarian Deleted Successfully']);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    public function getList($year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $rotarians = $this->getRotariansList($year);
        return response()->json(['rotarians' => $rotarians], 200);
    }

    public function getRegistered()
    {
        $year = $this->getActiveYear();
        $rotarians = $this->getRegisteredRotarians($year);
        return response()->json(['rotarians' => $rotarians], 200);
    }

    public function publicView($rotarian)
    {
        $year = $this->getActiveYear();
        $rotarian = $this->showRotarian($rotarian, $year);
        return view('public_viewing.rotarian', [
            'page_title' => $rotarian->rotarian_name,
            'rotarian' => $rotarian
        ]);
    }

    public function export($year) {
        return Excel::download(new RotariansExport($year), 'rotarians.xlsx');
        // return response()->json(['rotarians' => $this->exportRotarians($year)], 200);
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
