<?php 

namespace App\Http\Controllers\Traits;

use App\AutoreplyEmail;

trait AutoreplyEmailTrait
{
	function getAutoreplyEmailList() {
		$autoreply_emails = AutoreplyEmail::select('name as email_name', 'url_slug')->orderBy('id', 'asc')->get();
		return $autoreply_emails;
	}

	function showAutoreplyEmail($id) {
		$autoreply = AutoreplyEmail::where('url_slug', $id)->first();
		return $autoreply;
	}

	function updateAutoreply($request, $id) {
		$autoreply = AutoreplyEmail::where('url_slug', $id)->first();
        $autoreply->content = $request->content;
        if($autoreply->save()) {
        	return $autoreply;
        }
        return null;
	}
}