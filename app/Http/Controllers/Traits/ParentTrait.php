<?php

namespace App\Http\Controllers\Traits;

use App\ChildParent;
use App\ParentRegistration;
use App\ChildRegistration;
use App\BrushTracker;
use Illuminate\Support\Str;
use App\Children;
use Propaganistas\LaravelPhone\PhoneNumber;

trait ParentTrait
{
	public $reg_year;

	function checkParent($firstname, $lastname) {
		return (ChildParent::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->count());
	}

	function getParentUser($id) {
        return ChildParent::find($id)->user_id;
    }

	function addParent($request, $user_id, $year) {
		$parent = new ChildParent;
		$parent->user_id = $user_id;
		$parent->firstname = $request->contact_firstname;
		$parent->lastname = $request->contact_lastname;
		$parent->phone = $request->phone;
		$parent->formatted_phone = PhoneNumber::make($request->phone, 'CA');
		// $parent->formatted_phone = $request->phone;
		$parent->last_registration_year = $year;
		$parent->url_slug = Str::slug('parent '.$request->contact_firstname.' '.$request->contact_lastname, '-');
		if ($parent->save()) {
			return $parent;
		}
		return null;
	}

	function updateParent($request, $user_id, $parent_id, $year = null) {
		$parent = ChildParent::find($parent_id);
		$parent->user_id = $user_id;
		$parent->firstname = $request->contact_firstname;
		$parent->lastname = $request->contact_lastname;
		$parent->phone = $request->phone;
		// $parent->formatted_phone = $request->phone;
		$parent->formatted_phone = PhoneNumber::make($request->phone, 'CA');
		if (isset($year)) {
			$parent->last_registration_year = $year;
		}
		$parent->url_slug = Str::slug('parent '.$request->contact_firstname.' '.$request->contact_lastname, '-');
		if ($parent->save()) {
			return $parent;
		}
		return null;
	}

	function getParent($firstname, $lastname) {
		$parent = ChildParent::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->first();
		if (!empty($parent)) {
			return $parent->id;
		}
		return 0;
	}

	function checkIfParentIsRegistered($parent_id, $year) {
		$parent = ChildParent::find($parent_id);
        if(!empty($parent)) {
            return ($parent->last_registration_year == $year);
        }
        return 0;
	}

	function getParentList($year) {
		$this->reg_year = $year;
		$parents = ParentRegistration::rightJoin('child_parents', 'child_parents.id', '=', 'parent_registrations.child_parent_id')
			->leftJoin('users', 'child_parents.user_id', '=', 'users.id')
			->selectRaw('users.email as email, child_parents.id as id, child_parents.phone as phone, concat(child_parents.firstname, " ", child_parents.lastname) as parent_name, child_parents.url_slug as url_slug')
			->where('parent_registrations.year', $year)->distinct()->get();

		foreach($parents as $parent) {
			$children = ChildRegistration::rightJoin('children', 'children.id', '=', 'child_registrations.child_id')
				->selectRaw('children.id as id, children.firstname as firstname, children.url_slug as url_slug')
				->where('child_registrations.year', $year)
				->where('children.child_parent_id', $parent->id)
				->get();

			// $parent->parent_name = $parent->firstname." ".$parent->lastname;
			foreach($children as $child) {
				$brush_trackers = BrushTracker::where('child_id', $child->id)
					->where('year', $year)->get();
				$brush_count = 0;
				foreach($brush_trackers as $brush_tracker) {
					$brush_count += $brush_tracker->morning_brush;
					$brush_count += $brush_tracker->morning_floss;
					$brush_count += $brush_tracker->lunchtime_brush;
					$brush_count += $brush_tracker->lunchtime_floss;
					$brush_count += $brush_tracker->night_brush;
					$brush_count += $brush_tracker->night_floss;
					$brush_count += $brush_tracker->bonus_brush;
					$brush_count += $brush_tracker->bonus_floss;
				}
				$child->brush_count = $brush_count;
			}

			$parent->children = $children;
		}

		return $parents;
	}

	function showParent($parent) {
		$parent = ChildParent::whereRaw("lower(url_slug) = '".strtolower($parent)."'")->first();
		return $parent->firstname.' '.$parent->lastname;
	}

	function editParent($parent) {
		$parent = ChildParent::leftJoin('users', 'child_parents.user_id', '=', 'users.id')
			->select('child_parents.id as id', 'child_parents.firstname as contact_firstname', 'child_parents.lastname as contact_lastname', 'child_parents.phone as phone', 'users.email as email')
			->whereRaw("lower(url_slug) = '".strtolower($parent)."'")->first();
		return $parent;
	}

	function deleteParent($id) {
		return ChildParent::find($id)->delete();
	}

	function getRegisteredParentsCount($year) {
		return ChildParent::where('last_registration_year', $year)->count();
	}

	function addParentRegistration($id, $year) {
		$registration = new ParentRegistration;
		$registration->child_parent_id = $id;
		$registration->year = $year;
		return $registration->save();
	}

	function exportParents($year) {
		$this->reg_year = $year;
		$parents = ParentRegistration::rightJoin('child_parents', 'child_parents.id', '=', 'parent_registrations.child_parent_id')
			->leftJoin('users', 'child_parents.user_id', '=', 'users.id')
			->selectRaw('users.email as email, child_parents.id as id, child_parents.phone as phone, concat(child_parents.firstname, " ", child_parents.lastname) as parent_name')
			->where('parent_registrations.year', $year)->get();
		foreach($parents as $parent) {
			$children = ChildRegistration::rightJoin('children', 'children.id', '=', 'child_registrations.child_id')
				->selectRaw('children.firstname as firstname, children.lastname as lastname')
				->where('child_registrations.year', $year)
				->where('children.child_parent_id', $parent->id)
				->get();
			// $parent->parent_name = $parent->firstname." ".$parent->lastname;
			foreach($children as $child) {
				$brush_trackers = BrushTracker::where('child_id', $child->id)
					->where('year', $year)->get();
				$brush_count = 0;
				foreach($brush_trackers as $brush_tracker) {
					$brush_count += $brush_tracker->morning_brush;
					$brush_count += $brush_tracker->morning_floss;
					$brush_count += $brush_tracker->lunchtime_brush;
					$brush_count += $brush_tracker->lunchtime_floss;
					$brush_count += $brush_tracker->night_brush;
					$brush_count += $brush_tracker->night_floss;
					$brush_count += $brush_tracker->bonus_brush;
					$brush_count += $brush_tracker->bonus_floss;
				}
				$child->brush_count = $brush_count;
			}
			$parent->children = $children;
		}

		return $parents;
	}

}
