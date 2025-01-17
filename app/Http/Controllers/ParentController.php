<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChildParent;
use App\Http\Controllers\Traits\UserTrait;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\ParentTrait;
use App\Http\Controllers\Traits\ChildTrait;
use App\Http\Controllers\Traits\BrushtrackerTrait;
use Illuminate\Support\Facades\Validator;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParentRegistrationMail;
use Excel;
use App\Exports\ParentsExport;

class ParentController extends Controller
{
    use UserTrait;
    use YearTrait;
    use ParentTrait;
    use ChildTrait;
    use BrushtrackerTrait;

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
        return view('registration.parent', [
            'page_title' => 'Parent Registration'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParentRequest $request)
    {
        // return response()->json(['env' => config('app.env')]);
        // if(config('app.env') == 'staging') {
        //      $rules = [
        //     'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('parent_registration')]
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
        $parent_id = $this->getParent($request->contact_firstname, $request->contact_lastname);
        // return response()->json(['res' => $parent_id], 200);
        if($this->checkIfParentIsRegistered($parent_id, $year)) {
            return response()->json(['success' => false, 'msg' => 'Already Registered'], 400);
        }

        if ($parent_id > 0) {
            $parent_find = ChildParent::find($parent_id);
            $email_count = User::where('email', $request->email)->where('id', '!=', $parent_find->user_id)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->updateUser($request, $parent_find->user_id, 'parent');
        } else {
            $email_count = User::where('email', $request->email)->count();
            if($email_count > 0) {
                return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
            }
            $user = $this->addUser($request, 'parent');
        }


        if (!empty($user)) {
            if ($this->checkParent($request->contact_firstname, $request->contact_lastname)) {
                $parent = $this->updateParent($request, $user->id, $parent_id, $year);
            } else {
                $parent = $this->addParent($request, $user->id, $year);
            }
            if(!empty($parent)) {
                if ($this->addParentRegistration($parent->id, $year)) {
                    foreach ($request->children as $child) {
                        if (!$this->checkChild($child['firstname'], $child['lastname'], $parent->id)) {
                            $child1 = $this->addChild($child['firstname'], $child['lastname'], $parent->id, $year);
                        } else {
                            $child_id = $this->getChild($child['firstname'], $child['lastname'], $parent->id);
                            $child1 = $this->updateChild($child['firstname'], $child['lastname'], $child_id, $year);
                        }
                        $this->addChildRegistration($child1->id, $year);
                    }
                }
                if (!isset($request->selected_year)) {
                    if (app()->environment('local')) {
                        $cc = [];

                        collect(config('brushamania.emails.development'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });

                        Mail::to('dev@example.test')->bcc($cc)->send(new ParentRegistrationMail($parent));
                    } else {
                        $cc = [];

                        collect(config('brushamania.emails.production'))->each(function ($email) use (&$cc) {
                            $cc[] = ['email' => $email];
                        });

                        Mail::to($request->email)->bcc($cc)->queue(new ParentRegistrationMail($parent));
                    }
                }
                return response()->json(['success' => true, 'msg' => 'Registered Successfully']);
            }
            return response()->json(['success' => false, 'msg' => 'Something Went Wrong'], 400);
        }
        return response()->json(['success' => false, 'msg' => 'Something Went Wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($parent)
    {
        $parent = $this->showParent($parent);
        return response()->json(['parent' => $parent], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($parent)
    {
        $parent = $this->editParent($parent);
        return response()->json(['parent' => $parent], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParentRequest $request, $id)
    {
        $parent = ChildParent::find($id);

        $email_count = User::where('email', $request->email)->where('id', '!=', $parent->user_id)->count();
        if($email_count > 0) {
            return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
        }
        $user = $this->updateUser($request, $parent->user_id, 'parent');
        if (!empty($user)) {
            $parent = $this->updateParent($request, $user->id, $id);
            if (!empty($parent)) {
                return response()->json(['success' => true, 'msg' => 'Parent Updated Successfully', 'url_slug' => $parent->url_slug], 200);
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
        $user = $this->getParentUser($id);
        if ($this->deleteParent($id)) {
            $children = $this->getParentChildren($id);
            foreach ($children as $child) {
                if ($this->deleteChild($child->id)) {
                    $this->deleteChildBrushtracker($child->id);
                }
            }
            $this->deleteUser($user);
            return response()->json(['success' => true, 'msg' => 'Parent Deleted Successfully']);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    public function getList($year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $parents = $this->getParentList($year);
        return response()->json(['parents' => $parents], 200);
    }

    public function export($year) {
        return Excel::download(new ParentsExport($year), 'parents.xlsx');
        // return response()->json(['parents' => $this->exportParents($year)], 200);
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
