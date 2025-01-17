<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Http\Controllers\Traits\UserTrait;
use App\Http\Controllers\Traits\PoliticianTrait;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    use UserTrait;
    use PoliticianTrait;
    use YearTrait;
    
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $email_count = User::where('email', $request->email)->count();
        if($email_count > 0) {
            return response()->json(['success' => false, 'msg' => 'User Already Used'], 400);
        }
        $user = $this->addUser($request, $request->role);
        if (!empty($user)) {
            return response()->json(['success' => true, 'msg' => 'User Added Successfully'], 200);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        $user = Auth::user();
        $user = $this->updateUser($request, $user->id, $user->role);
        if(!empty($user)) {
            return response()->json(['success' => true, 'msg' => 'User Profile Update Successfully'], 200);
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
        $this->deleteUser($id);
        return response()->json(['success' => true, 'msg' => 'User Deleted Successfully']);
    }

    public function getRole()
    {
        $role = Auth::user()->role;
        return response()->json(['role' => $role], 200);
    }

    public function getUserSlug()
    {
        $user = Auth::user();
        $url_slug = null;
        if($user->role == 'parent') {
            $url_slug = $user->child_parent->url_slug;
        } else if($user->role == 'school') {
            $url_slug = $user->school->url_slug;
        } else if($user->role == 'dentist') {
            $url_slug = $user->dentist->url_slug;
        } else if($user->role == 'rotarian') {
            $url_slug = $user->rotarian->url_slug;
        }
        return response()->json(['url_slug' => $url_slug], 200);
    }

    public function getUserProfile()
    {
        $user = Auth::user();
        if($user->role == 'school') {
            $contact_salutation = $user->school->contact_salutation;
            return response()->json(['id' => $user->id, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'email' => $user->email, 'contact_salutation' => $contact_salutation], 200);
        } else if($user->role == 'dentist') {
            $contact_salutation = $user->dentist->contact_salutation;
            return response()->json(['id' => $user->id, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'email' => $user->email, 'contact_salutation' => $contact_salutation], 200);
        }
        return response()->json(['id' => $user->id, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'email' => $user->email], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        if (Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
            $user->password = bcrypt($request->new_password);
            $user->save();
            return response()->json(['success' => true, 'msg' => 'Password Updated Successfully'], 200);
        }
        return response()->json(['success' => false, 'msg' => 'Incorrect Password'], 400);
    }

    public function updateInitialPassword(Request $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->updated_password = 1;
        $user->save();
        return response()->json(['success' => true, 'msg' => 'Password Updated Successfully'], 200);
    }

    public function getAdminList()
    {
        $year = $this->getActiveYear();
        $users = $this->getUsersList('all');
        return response()->json(['users' => $users], 200);
    }

    public function getMailList($receiver, $address = null) {
        if ($receiver == 'politician') {
            $emails = $this->getPoliticiansEmailList($address);
        } else {
            $emails = $this->getUsersList($receiver, $address);
        }
        return response()->json(['emails' => $emails], 200);
    }

}
