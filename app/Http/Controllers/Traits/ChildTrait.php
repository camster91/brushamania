<?php 

namespace App\Http\Controllers\Traits;

use App\Child;
use App\ChildRegistration;
use App\ChildParent;
use App\BrushTracker;
use App\User;
use Auth;
use Illuminate\Support\Str;

trait ChildTrait
{
	public $reg_year;

	function checkChild($firstname, $lastname, $parent_id) {
		return (Child::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->where('child_parent_id', $parent_id)->count());		
	}

	function addChild($firstname, $lastname, $parent_id, $year) {
		$child = new Child;
		$child->firstname = $firstname;
		$child->lastname = $lastname;
		$child->last_registration_year = $year;
		$child->child_parent_id = $parent_id;
		$child->url_slug = Str::slug('child '.$firstname.' '.$lastname, '-');
		if ($child->save()) {
			return $child;
		}
		return null;
	}

	function updateChild($firstname, $lastname, $child_id, $year = null) {
		$child = Child::find($child_id);
		$child->firstname = $firstname;
		$child->lastname = $lastname;
		$child->url_slug = Str::slug('child '.$firstname.' '.$lastname, '-');
		if (isset($year)) {
			$child->last_registration_year = $year;
			$child->complete_brushes_flosses = false;
		}
		if ($child->save()) {
			return $child;
		}
		return null;
	}

	function getChild($firstname, $lastname, $parent_id) {
		$child = Child::whereRaw("lower(firstname) = '".strtolower($firstname)."'")->whereRaw("lower(lastname) = '".strtolower($lastname)."'")->where('child_parent_id', $parent_id)->first();
		if (!empty($child)) {
			return $child->id;
		}
		return null;
	}

	function getChildrenList($year) {
		$this->reg_year = $year;
		// $children = Child::with(['child_parent', 'brush_trackers' => function($query) {
		// 	$query->where('year', $this->reg_year);
		// }])->orderBy('updated_at', 'desc')->get();
		$children = ChildRegistration::rightJoin('children', 'children.id', '=', 'child_registrations.child_id')
			->selectRaw('children.id as id, children.child_parent_id as child_parent_id, concat(children.firstname, " ", children.lastname) as child_name, children.url_slug as url_slug')
			->where('child_registrations.year', $year)->get();
		foreach($children as $child) {
			$child_parent = ChildParent::selectRaw('url_slug, concat(firstname, " ", lastname) as name')
				->where('id', $child->child_parent_id)->first();
			$child->child_parent = $child_parent;
			$brush_count = 0;
			$brush_trackers = BrushTracker::where('child_id', $child->id)
					->where('year', $year)->get();
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
			// $child->brushtrackers = $brush_trackers;
			if($brush_count >= 100) {
				$child->certificate_status = true;
			} else {
				$child->certificate_status = false;
			}
		}
		return $children;
	}

	function showChild($child) {
		$child = Child::whereRaw("lower(url_slug) = '".strtolower($child)."'")->first();
		return $child->firstname.' '.$child->lastname;
	}

	function editChild($child) {
		$child = Child::whereRaw("lower(url_slug) = '".strtolower($child)."'")->first();
		return $child;
	}

	function getParentChildren($parent_id) {
		return Child::where('child_parent_id', $parent_id)->get();
	}

	function deleteChild($id) {
		return Child::find($id)->delete();
	}

	function getRegisteredChildrenCount($year) {
		return Child::where('last_registration_year', $year)->count();
	}

	function getChildrenOfParentList() {
		$user = Auth::user()->id;
		$parent = ChildParent::where('user_id', $user)->first()->id;
		if (!empty($parent)) {
			$children = Child::select('id', 'firstname', 'lastname', 'url_slug')
				->where('child_parent_id', $parent)->get();
			return $children;
		}
		return null;
	}

	function addChildRegistration($id, $year) {
		$registration = new ChildRegistration;
		$registration->child_id = $id;
		$registration->year = $year;
		return $registration->save();
	}

	function exportChildren($year) {
		$this->reg_year = $year;
		// $children = Child::with(['child_parent', 'brush_trackers' => function($query) {
		// 	$query->where('year', $this->reg_year);
		// }])->orderBy('updated_at', 'desc')->get();
		$children = ChildRegistration::rightJoin('children', 'children.id', '=', 'child_registrations.child_id')
			->selectRaw('children.id as id, children.child_parent_id as child_parent_id, concat(children.firstname, " ", children.lastname) as child_name, date_format(child_registrations.created_at, "%M %d, %Y") as registration_date')
			->where('child_registrations.year', $year)->get();
		foreach($children as $child) {
			$child_parent = ChildParent::selectRaw('concat(firstname, " ", lastname) as name, user_id, phone')
				->where('id', $child->child_parent_id)->first();
			$child->child_parent = $child_parent;
			$user = User::find($child_parent->user_id);
			$child->user = $user->email;
			$brush_count = 0;
			$brush_trackers = BrushTracker::where('child_id', $child->id)
					->where('year', $year)->get();
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
			$child->days_brushing = count($brush_trackers);
		}
		return $children;
	}
}