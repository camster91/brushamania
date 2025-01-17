<?php

namespace App\Http\Controllers\Traits;

use App\MailBlast;
use App\User;
use App\Politician;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailBlastMail;
use Illuminate\Support\Str;

trait MailBlastTrait
{
	function addMailBlast($request) {
		$mailblast = new MailBlast;
		$mailblast->name = $request->name;
		$mailblast->subject = $request->subject;
        $mailblast->receiver = $request->receiver;
		$mailblast->address = $request->address;
		$mailblast->year = $request->year;
        $mailblast->content = $request->content;
        $mailblast->url_slug = Str::slug('mailblast '.$request->receiver.' '.$request->name, '-');
        if($mailblast->save()) {
        	return $mailblast;
        }
        return null;
	}

	function updateMailBlast($request, $id) {
		$mailblast = MailBlast::find($id);
		$mailblast->name = $request->name;
		$mailblast->subject = $request->subject;
        $mailblast->receiver = $request->receiver;
		$mailblast->address = $request->address;
		$mailblast->year = $request->year;
        $mailblast->content = $request->content;
        $mailblast->url_slug = Str::slug('mailblast '.$request->receiver.' '.$request->name, '-');
        if($mailblast->save()) {
        	return $mailblast;
        }
        return null;
	}

	function saveMailBlastRecipients($request) {
		$receivers = '';
		foreach($request->email_addresses as $email_address) {
			$receivers.=$email_address['email'].',';
		}
		$mail_blast = MailBlast::find($request->id);
		$mail_blast->receiver_list = $receivers;
		return $mail_blast->save();
	}

	function sendMailBlast($request) {
		// if ($request->receiver == 'politician') {
		// 	$receivers = Politician::all();
		// }
		// else {
		// 	$receivers = User::where('role', $request->receiver)->get();

		// }
		if(config('app.env') == 'local') {
			$cc = [
                ['email' => 'abbasumaru44@gmail.com']
			];
			$result = array_merge($request->email_addresses, $cc);
			foreach($request->email_addresses as $email_address) {
				Mail::to($email_address['email'])->queue(new MailBlastMail($request->content, $request->subject));
			}
			foreach($cc as $cc2) {
				Mail::to($cc2)->queue(new MailBlastMail($request->content, $request->subject));
			}
		} else {
			$cc = [
                ['email' => 'jennifer.boyd@hotmail.com'],
				['email' => 'dr.chouljian@scarboroughnorthdental.ca'],
				['email' => 'abbasumaru44@gmail.com']
			];
			$result = array_merge($request->email_addresses, $cc);
			foreach($request->email_addresses as $email_address) {
				Mail::to($email_address['email'])->queue(new MailBlastMail($request->content, $request->subject));
			}

			foreach($cc as $cc2) {
				Mail::to($cc2)->queue(new MailBlastMail($request->content, $request->subject));
			}
			// Mail::bcc($result)->queue(new MailBlastMail($request->content, $request->subject));
		}

		return true;

	}

	function getMailBlastList() {
		$mailblasts = MailBlast::select('id', 'name as template_name', 'subject', 'url_slug')
		->selectRaw('date_format(created_at, "%M %d, %Y %h:%i %p") as date_created, CONCAT(UCASE(LEFT(receiver, 1)), SUBSTRING(receiver, 2), "s") as receiver')
		->orderBy('created_at', 'desc')->get();
		return $mailblasts;
	}

	function showMailBlast($id) {
		$mailblast = MailBlast::where('url_slug', $id)->first();
		$mailblast->recipients = explode(",", $mailblast->receiver_list);
		return $mailblast;
	}

	function deleteMailBlast($id) {
		return MailBlast::find($id)->delete();
	}
}