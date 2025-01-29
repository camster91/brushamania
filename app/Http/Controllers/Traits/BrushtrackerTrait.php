<?php

namespace App\Http\Controllers\Traits;

use App\BrushTracker;
use App\ChildParent;
use App\ChildRegistration;
use App\Child;
use App\User;
use Auth;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BrushamaniaWinnersMail;
use App\Jobs\ProcessBrushtrackerJob;

trait BrushtrackerTrait
{
	function deleteChildBrushtracker($child_id) {
		return BrushTracker::where('child_id', $child_id)->delete();
	}

	function getBrushtrackerChildrenList($year) {
		$user = Auth::user()->id;
		$parent = ChildParent::where('user_id', $user)->first()->id;
		if (!empty($parent)) {
			$children = Child::selectRaw('id, concat(firstname, " ", lastname) as child_name, last_registration_year')
				->where('child_parent_id', $parent)->get();
			return $children;
		}
		return null;
	}



	function getListBrushtracker($year, $child, $is_active) {
		if($child->last_registration_year != $year) {
			$child->brush_count = 0;
			$child->is_registered = false;
			return $child;
		}
		$child->is_registered = true;
		$brushtrackers = BrushTracker::where('child_id', $child->id)->where('year', $year)->get();
		$brush_count = 0;
		foreach ($brushtrackers as $brushtracker) {
			$brush_count += $brushtracker->morning_brush;
			$brush_count += $brushtracker->morning_floss;
			$brush_count += $brushtracker->lunchtime_brush;
			$brush_count += $brushtracker->lunchtime_floss;
			$brush_count += $brushtracker->night_brush;
			$brush_count += $brushtracker->night_floss;
			$brush_count += $brushtracker->bonus_brush;
			$brush_count += $brushtracker->bonus_floss;
		}
		$child->days = count($brushtrackers);
		$child->brush_count = $brush_count;
		if ($brush_count >= 100) {
			$child->certificate_status = true;
		} else {
			$child->certificate_status = false;
		}
		// if ($is_active) {
			$brush_today = BrushTracker::where('child_id', $child->id)->whereDate('brush_date', date('Y-m-d', strtotime("-3 hours")))->first();
			if (empty($brush_today)) {
				$brush_today = new BrushTracker;
				$brush_today->child_id = $child->id;
				$brush_today->year = $year;
				$brush_today->morning_brush = 0;
				$brush_today->morning_floss = 0;
				$brush_today->lunchtime_brush = 0;
				$brush_today->lunchtime_floss = 0;
				$brush_today->night_brush = 0;
				$brush_today->night_floss = 0;
				$brush_today->bonus_brush = 0;
				$brush_today->bonus_floss = 0;
				$brush_today->brush_date = date('Y-m-d', strtotime("-3 hours"));
				$brush_today->save();
			}
			$child->brush_today = $brush_today;
			$brush_yesterday = BrushTracker::where('child_id', $child->id)->whereDate('brush_date', date('Y-m-d', strtotime("-27 hours")))->first();
			// if (empty($brush_yesterday)) {
			// 	$brush_yesterday = new BrushTracker;
			// 	$brush_yesterday->child_id = $child->id;
			// 	$brush_yesterday->year = $year;
			// 	$brush_yesterday->morning_brush = 0;
			// 	$brush_yesterday->morning_floss = 0;
			// 	$brush_yesterday->lunchtime_brush = 0;
			// 	$brush_yesterday->lunchtime_floss = 0;
			// 	$brush_yesterday->night_brush = 0;
			// 	$brush_yesterday->night_floss = 0;
			// 	$brush_yesterday->bonus_brush = 0;
			// 	$brush_yesterday->bonus_floss = 0;
			// 	$brush_yesterday->brush_date = date('Y-m-d', strtotime("-27 hours"));
			// 	$brush_yesterday->save();
			// }
			$child->brush_yesterday = $brush_yesterday;
		// }

		return $child;
	}


	// function getListBrushtracker($year) {
	// 	$user = Auth::user()->id;
	// 	$parent = ChildParent::where('user_id', $user)->first()->id;
	// 	if (!empty($parent)) {
	// 		$children = Child::selectRaw('id, concat(firstname, " ", lastname) as child_name, last_registration_year')
	// 			->where('child_parent_id', $parent)->get();
	// 		foreach ($children as $child) {
	// 			if($child->last_registration_year != $year) {
	// 				$child->brush_count = 0;
	// 				$child->is_registered = false;
	// 				continue;
	// 			}
	// 			$child->is_registered = true;
	// 			$brushtrackers = BrushTracker::where('child_id', $child->id)->where('year', $year)->get();
	// 			$brush_count = 0;
	// 			foreach ($brushtrackers as $brushtracker) {
	// 				$brush_count += $brushtracker->morning_brush;
	// 				$brush_count += $brushtracker->morning_floss;
	// 				$brush_count += $brushtracker->lunchtime_brush;
	// 				$brush_count += $brushtracker->lunchtime_floss;
	// 				$brush_count += $brushtracker->night_brush;
	// 				$brush_count += $brushtracker->night_floss;
	// 				$brush_count += $brushtracker->bonus_brush;
	// 				$brush_count += $brushtracker->bonus_floss;
	// 			}
	// 			$child->brush_count = $brush_count;
	// 			if ($brush_count >= 100) {
	// 				$child->certificate_status = true;
	// 			} else {
	// 				$child->certificate_status = false;
	// 			}
	// 			$brush_today = BrushTracker::where('child_id', $child->id)->whereDate('brush_date', date('Y-m-d'))->first();
	// 			if (empty($brush_today)) {
	// 				$brush_today = new BrushTracker;
	// 				$brush_today->child_id = $child->id;
	// 				$brush_today->year = $year;
	// 				$brush_today->morning_brush = 0;
	// 				$brush_today->morning_floss = 0;
	// 				$brush_today->lunchtime_brush = 0;
	// 				$brush_today->lunchtime_floss = 0;
	// 				$brush_today->night_brush = 0;
	// 				$brush_today->night_floss = 0;
	// 				$brush_today->bonus_brush = 0;
	// 				$brush_today->bonus_floss = 0;
	// 				$brush_today->brush_date = date('Y-m-d');
	// 				$brush_today->save();
	// 			}
	// 			$child->brush_today = $brush_today;
	// 			$brush_yesterday = BrushTracker::where('child_id', $child->id)->whereDate('brush_date', date('Y-m-d', strtotime("-1 days")))->first();
	// 			if (empty($brush_yesterday)) {
	// 				$brush_yesterday = new BrushTracker;
	// 				$brush_yesterday->child_id = $child->id;
	// 				$brush_yesterday->year = $year;
	// 				$brush_yesterday->morning_brush = 0;
	// 				$brush_yesterday->morning_floss = 0;
	// 				$brush_yesterday->lunchtime_brush = 0;
	// 				$brush_yesterday->lunchtime_floss = 0;
	// 				$brush_yesterday->night_brush = 0;
	// 				$brush_yesterday->night_floss = 0;
	// 				$brush_yesterday->bonus_brush = 0;
	// 				$brush_yesterday->bonus_floss = 0;
	// 				$brush_yesterday->brush_date = date('Y-m-d', strtotime("-1 days"));
	// 				$brush_yesterday->save();
	// 			}
	// 			$child->brush_yesterday = $brush_yesterday;
	// 		}
	// 		return $children;
	// 	}
	// 	return null;
	// }

	function updateChildBrushtracker($request) {
		$brushtracker = Brushtracker::where('child_id', $request->child_id)->where('brush_date', $request->brush_date)->first();
		if(empty($brushtracker)) {
			$brushtracker = new Brushtracker;
		}
		$brushtracker->year = $this->getActiveBrushtrackerYear($request->brush_date);
		$brushtracker->child_id = $request->child_id;
		$brushtracker->brush_date = $request->brush_date;
		$brushtracker->morning_brush = $request->morning_brush;
		$brushtracker->morning_floss = $request->morning_floss;
		$brushtracker->lunchtime_brush = $request->lunchtime_brush;
		$brushtracker->lunchtime_floss = $request->lunchtime_floss;
		$brushtracker->night_brush = $request->night_brush;
		$brushtracker->night_floss = $request->night_floss;
		$brushtracker->bonus_brush = $request->bonus_brush;
		$brushtracker->bonus_floss = $request->bonus_floss;
		$brushtracker->save();
		return $brushtracker;
	}

	function updateBrushtracker($request, $id, $year) {
		$brushtracker = Brushtracker::find($id);
		$brushtracker->morning_brush = $request->morning_brush;
		$brushtracker->morning_floss = $request->morning_floss;
		$brushtracker->lunchtime_brush = $request->lunchtime_brush;
		$brushtracker->lunchtime_floss = $request->lunchtime_floss;
		$brushtracker->night_brush = $request->night_brush;
		$brushtracker->night_floss = $request->night_floss;
		$brushtracker->bonus_brush = $request->bonus_brush;
		$brushtracker->bonus_floss = $request->bonus_floss;
		$is_saved = $brushtracker->save();
		$child = $brushtracker->child;
		$brushtrackers = BrushTracker::where('child_id', $child->id)->where('year', $year)->get();
		$brush_count = 0;
		foreach ($brushtrackers as $brushtracker) {
			$brush_count += $brushtracker->morning_brush;
			$brush_count += $brushtracker->morning_floss;
			$brush_count += $brushtracker->lunchtime_brush;
			$brush_count += $brushtracker->lunchtime_floss;
			$brush_count += $brushtracker->night_brush;
			$brush_count += $brushtracker->night_floss;
			$brush_count += $brushtracker->bonus_brush;
			$brush_count += $brushtracker->bonus_floss;
		}
		// $child->brush_count = $brush_count;

		// $filename = Str::slug($child->firstname.' '.$child->lastname.' '.$year);
		// 	$data = [
		// 		'firstname' => $child->firstname,
		// 		'lastname' => $child->lastname
		// 	];
		// 	$pdf = PDF::loadView('pdf.certificate', $data);
		// 	$pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
		// 	Mail::to('earl@dentistrybusiness.com')->queue(new BrushamaniaWinnersMail('Brush-a-mania Winners', 'pdf/'.$filename.'.pdf'));
		// $child2 = Child::find($child->id);

		if ($brush_count >= 100) {
			$child_registration = ChildRegistration::where('child_id', $child->id)->where('year', $year)->first();

			if(!$child_registration->sent_certificate) {
				// return $child_registration;
				$child->complete_brushes_flosses = true;
				$child->save();
				$filename = Str::slug($child->firstname.' '.$child->lastname.' '.$year);
				$data = [
					'firstname' => $child->firstname,
					'lastname' => $child->lastname,
					'year' => $year
				];
				$pdf = PDF::loadView('pdf.certificate', $data);
				$pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
				if(config('app.env') == 'local') {
					Mail::to('abbasumaru44@gmail.com')->queue(new BrushamaniaWinnersMail('Brush-a-mania Winners '.$child->firstname.' '.$child->lastname, 'http://localhost:8000/pdf/'.$filename.'.pdf'));
				} else {
					$cc = [
                        ['email' => 'jennifer.boyd@hotmail.com'],
                        ['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
                        ['email' => 'abbasumaru44@gmail.com']
					];
					Mail::to(Auth::user()->email)->bcc($cc)->queue(new BrushamaniaWinnersMail('Brush-a-mania Certificate of Achievement '.$child->firstname.' '.$child->lastname, 'https://app.brushamania.ca/pdf/'.$filename.'.pdf'));
				}
				$child_registration->sent_certificate = true;
				$child_registration->save();
			}


		} else {
			$child->complete_brushes_flosses = false;
			$child->save();
		}


		// $filename = Str::slug($child->firstname.' '.$child->lastname.' '.$year);
		// $data = [
		// 	'firstname' => $child->firstname,
		// 	'lastname' => $child->lastname
		// ];
		// $pdf = PDF::loadView('pdf.certificate', $data);
		// $pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
		// Mail::to('earlbulasa@gmail.com')->send(new BrushamaniaWinnersMail('Brush-a-mania Winners', 'pdf/'.$filename.'.pdf'));



		return $is_saved;
	}

	function getChildrenWithCompleteBrushes($year) {
		$children = Child::leftJoin('child_parents', 'children.child_parent_id', '=', 'child_parents.id')->selectRaw('children.url_slug as child_url_slug, child_parents.url_slug as parent_url_slug, concat(children.firstname, " ", children.lastname) as child, concat(child_parents.firstname, " ", child_parents.lastname) as parent, date_format(children.updated_at, "%M %d, %Y") as completed_on')->where('children.complete_brushes_flosses', 1)->orderBy('children.updated_at', 'desc')->get();
		return $children;
	}

	function sendCertificate($request){
		$year = $this->getActiveYear();
		$child = Child::find($request->id);
		$child_registration = ChildRegistration::where('child_id', $child->id)->where('year', $year)->first();
		$child_parent = ChildParent::find($child->child_parent_id);
		$parent_user = User::find($child_parent->user_id);
		$filename = Str::slug($child->firstname.' '.$child->lastname.' '.$year);
		$data = [
			'firstname' => $child->firstname,
			'lastname' => $child->lastname,
			'year' => $year
		];
		$pdf = PDF::loadView('pdf.certificate', $data);
		$pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
		if(config('app.env') == 'local') {
			Mail::to('abbasumaru44@gmail.com')->queue(new BrushamaniaWinnersMail('Brush-a-mania Winners '.$child->firstname.' '.$child->lastname, 'http://localhost:8000/pdf/'.$filename.'.pdf'));
		} else {
			$bcc = [
				['email' => 'jennifer.boyd@hotmail.com'],
				['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
				['email' => 'abbasumaru44@gmail.com']
			];
			Mail::to($parent_user->email)->bcc($cc)->queue(new BrushamaniaWinnersMail('Brush-a-mania Certificate of Achievement for '.$child->firstname.' '.$child->lastname, 'https://app.brushamania.ca/pdf/'.$filename.'.pdf'));
			// Mail::to('earl@dentistrybusiness.com')->queue(new BrushamaniaWinnersMail('Brush-a-mania Winners', 'https://app.brushamania.ca/pdf/'.$filename.'.pdf'));
		}
		$child_registration->sent_certificate = true;
		$child_registration->save();
	}

	function sendCertificateAll($request) {
		// $year = $this->getActiveYear();
		$year = $request->year;
		$children = Child::where('complete_brushes_flosses', true)->where('last_registration_year', $year)->get();
		// $children = ChildRegistration::rightJoin('children', 'children.id', '=', 'child_registrations.child_id')
		// 	->selectRaw('children.id as id, children.child_parent_id as child_parent_id, children.firstname as firstname, children.lastname as lastname')
		// 	->where('children.complete_brushes_flosses', true)
		// 	->where('child_registrations.year', $year)->get();
		// $parent_users = [];
		// ProcessBrushtrackerJob::dispatch($children, $year);

		foreach($children as $child) {
			$child_parent = ChildParent::find($child->child_parent_id);
			$parent_user = User::find($child_parent->user_id);

			$child_registration = ChildRegistration::where('child_id', $child->id)->where('year', $year)->first();

			// $parent_users[] = $parent_user;
			$filename = Str::slug($child->firstname.' '.$child->lastname.' '.$year);
			$data = [
				'firstname' => $child->firstname,
				'lastname' => $child->lastname,
				'year' => $year
			];
			$pdf = PDF::loadView('pdf.certificate', $data);
			$pdf->setPaper('a4')->save('pdf/'.$filename.'.pdf');
			if(config('app.env') == 'local') {
				$cc = [
					['email' => 'abbasumaru44@gmail.com']
				];
				Mail::to('appdev@dentistfind.com')->bcc($cc)->queue(new BrushamaniaWinnersMail('Brush-a-mania Certificate of Achievement '.$child->firstname.' '.$child->lastname, 'http://localhost:8000/pdf/'.$filename.'.pdf'));
			} else {
				$cc = [
					['email' => 'jennifer.boyd@hotmail.com'],
					['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
					['email' => 'abbasumaru44@gmail.com']
				];
				Mail::to($parent_user->email)->bcc($cc)->queue(new BrushamaniaWinnersMail('Brush-a-mania Certificate of Achievement '.$child->firstname.' '.$child->lastname, 'https://app.brushamania.ca/pdf/'.$filename.'.pdf'));
			}
			$child_registration->sent_certificate = true;
			$child_registration->save();
		}
		// return $parent_users;
	}

	function resetBrushtrackerDays($child_id) {
		$year = $this->getActiveYear();
		Brushtracker::where('child_id', $child_id)->where('year', $year)->delete();
	}
}