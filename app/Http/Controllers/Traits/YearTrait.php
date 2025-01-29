<?php 

namespace App\Http\Controllers\Traits;

use App\Child;
use App\BrushTracker;
use DateTime;

trait YearTrait
{
	function isChildTrackingActive($child_id, $year = '') {
		$brushtrackers = BrushTracker::where('child_id', $child_id)->where('year', $year)->get();
		if(count($brushtrackers) > 30) {
			return false;
		}
		$current_date = new DateTime;
		$tracking_start_date_formatted = $this->brushTrackingStartDateFormatted($year);

		$current_date_timestamp = strtotime($current_date->format('Y-m-d H:i:s'));
		$tracking_start_date_timestamp = strtotime($tracking_start_date_formatted);
		if($this->isChildRegisteredForCurrentYear($child_id, $year)) {
			if($current_date_timestamp < $tracking_start_date_timestamp) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return false;
		}
	}

	function isChildRegisteredForCurrentYear($child_id, $year) {
		$child = Child::find($child_id);
		return $child->last_registration_year == $year;
	}

	function brushTrackingStartDateFormatted($year = '') {
		$tracking_start_date = $this->brushTrackingStartDate($year);
    	$tracking_start_date = new DateTime($tracking_start_date. ' 00:00:00');
    	return $tracking_start_date->format('Y-m-d H:i:s');
	}

	function brushTrackingStartDate($year = '') {
		if(empty($year)) {
	    	$year = $this->getActiveYear();
	    }

	    $start_date = date("Y-m-d", strtotime($year."-04-01"));
	    return $start_date;
	}

	function getActiveYear() {
		if($this->isCurrentYearActive()) {
			return intval(date('Y'));
		}
		else {
			return intval((date('Y')+1));
		}
	}

	function isCurrentYearActive() {
		if(date('m') < 6) {
			return true;
		} else {
			return false;
		}
	}

	function getActiveBrushtrackerYear($brush_date) {
		if($this->isCurrenctBrushtrackerActive($brush_date)) {
			return intval(date('Y'));
		}
		else {
			return intval((date('Y')+1));
		}
	}

	function isCurrenctBrushtrackerActive($brush_date) {
		if(date('m', strtotime($brush_date)) < 6) {
			return true;
		} else {
			return false;
		}
	}
	
}