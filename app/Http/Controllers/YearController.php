<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\SchoolTrait;
use App\Http\Controllers\Traits\DentistTrait;
use App\Http\Controllers\Traits\RotarianTrait;
use App\Http\Controllers\Traits\ParentTrait;
use App\Http\Controllers\Traits\ChildTrait;
use App\BrushTracker;
use DB;

class YearController extends Controller
{
	use YearTrait;
	use SchoolTrait;
	use DentistTrait;
	use RotarianTrait;
	use ParentTrait;
	use ChildTrait;

    public function getYear() {
    	return response()->json(['year' => $this->getActiveYear()], 200);
    }

    public function getRegisteredCount() {
    	$year = $this->getActiveYear();
        $schools = $this->getRegisteredSchoolsCount($year);
        $dentists = $this->getRegisteredDentistsCount($year);
        $rotarians = $this->getRegisteredRotariansCount($year);
        $parents = $this->getRegisteredParentsCount($year);
        $children = $this->getRegisteredChildrenCount($year);
        return response()->json([
        	'schools' => $schools,
        	'dentists' => $dentists,
        	'rotarians' => $rotarians,
        	'parents' => $parents,
        	'children' => $children
        ], 200);
    }

    public function isBrushTrackingActive($id) {
        $year = $this->getActiveYear();
        $is_active = $this->isChildTrackingActive($id, $year);
        // if (!$is_active) {
        //     BrushTracker::where('child_id', $id)->whereDate('created_at', DB::raw('CURDATE()'))->delete();
        // }
        return response()->json([
            'is_active' => $is_active
        ], 200);
    }
}
