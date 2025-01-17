<?php 

namespace App\Http\Controllers\Traits;


use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreateMail;
use Illuminate\Support\Str;
use App\School;
use App\ChildParent;
use App\Dentist;
use App\Rotarian;
use Auth;

trait UserTrait
{
	function getUserViaId($id) {
		$user = User::find($id);
		return $user;
	}
	function checkUser($email, $role) {
		$user = User::where('email', $email)->first();
		if(!empty($user)) {
			if($user->role != $role) {
				return 'exist';
			}
			return 'update';
		}
		return 'add';
	}

	function addUser($request, $role) {
		$user = new User();
		$user->firstname = $request->contact_firstname;
		$user->lastname = $request->contact_lastname;
		$user->email = $request->email;
		$randstr = Str::random(20);
		// $randstr = 'password';
		$user->password = bcrypt($randstr);
		$user->role = $role;
		if ($role == 'parent') {
			$user->updated_password = 1;
		}
		if($user->save()) {
			if ($role == 'admin' || $role == 'student' || $role == 'rotarian') {
				if (isset($request->selected_year)) {
		            if(config('app.env') == 'local') {
						Mail::to('earl@dentistrybusiness.com')->send(new UserCreateMail($randstr, $request->email));
					} else {
						Mail::to($request->email)->queue(new UserCreateMail($randstr, $request->email));
					}
		        }
			}
			$user->randstr = $randstr;
			return $user;
		}
		return null;
	}

	function updateUser($request, $id, $role) {
		$user = User::find($id);
		$user->firstname = $request->contact_firstname;
		$user->lastname = $request->contact_lastname;
		$user->email = $request->email;
		$user->role = $role;
		if($user->save()) {
			if($role == 'school') {
				$school = $user->school;
				$school->contact_salutation = $request->contact_salutation;
				$school->save();
			} else if($role == 'dentist') {
				$dentist = $user->dentist;
				$dentist->contact_salutation = $request->contact_salutation;
				$dentist->save();
			}
			return $user;
		}
		return null;
	}

	function getUser($email) {
		$user = User::where('email', $email)->first();
		if(!empty($user)) {
			return $user->id;
		}
		return 0;
	}

	function deleteUser($id) {
		return User::find($id)->delete();
	}

	function getUsersList($role, $year = null) {
		if ($role == 'all') {
			$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email, CONCAT(UCASE(LEFT(role, 1)), SUBSTRING(role, 2)) as role')->where('id', '!=', Auth::user()->id)->orderBy('email', 'asc')->get();
		} else {
			if ($role == 'admin') {
				$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email, CONCAT(UCASE(LEFT(role, 1)), SUBSTRING(role, 2)) as role')->where('id', '!=', Auth::user()->id)->where('role', 'admin')->orWhere('role', 'student')->orderBy('email', 'asc')->get();
			} else if ($role == 'school') {
				if($year == 'All') {
					$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('id', '!=', Auth::user()->id)->where('role', $role)->orderBy('email', 'asc')->get();
				} else {
					$users = School::selectTaw('users.id as id, concat(users.firstname, " ", users.lastname) as name, users.email')->join('users', 'users.id', 'schools.user_id')->where('schools.last_registration_year', $year)->get();
				}
			} else if ($role == 'parent') {
				if($year == 'All') {
					$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('id', '!=', Auth::user()->id)->where('role', $role)->orderBy('email', 'asc')->get();
				} else {
					$users = ChildParent::selectRaw('users.id as id, concat(users.firstname, " ", users.lastname) as name, users.email')->join('users', 'users.id', 'child_parents.user_id')->where('child_parents.last_registration_year', $year)->get();
				}
			} else if ($role == 'dentist') {
				if($year == 'All') {
					$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('id', '!=', Auth::user()->id)->where('role', $role)->orderBy('email', 'asc')->get();
				} else {
					$users = Dentist::selectRaw('users.id as id, concat(users.firstname, " ", users.lastname) as name, users.email')->join('users', 'users.id', 'dentists.user_id')->where('dentists.last_registration_year', $year)->get();
				}
			} else if ($role == 'rotarian') {
				if($year == 'All') {
					$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('id', '!=', Auth::user()->id)->where('role', $role)->orderBy('email', 'asc')->get();
				} else {
					$users = Rotarian::selectRaw('users.id as id, concat(users.firstname, " ", users.lastname) as name, users.email')->join('users', 'users.id', 'rotarians.user_id')->where('rotarians.last_registration_year', $year)->get();
				}
			}
			// } else {
			// 	$users = User::selectRaw('id, concat(firstname, " ", lastname) as name, email')->where('id', '!=', Auth::user()->id)->where('role', $role)->orderBy('email', 'asc')->get();
				
			// }
			
		}
		return $users;
	}
}