<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rotarian;
use Auth;

class UpdateRotarianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rotarian = Rotarian::find(\Request::get('id'));
        $user_id = $rotarian->user->id;
        return [
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id,
            'club_name' => 'required',
            'is_organizing_committee' => 'required|boolean',
            'is_lootbag_committee' => 'required|boolean',
            'is_pr_media_committee' => 'required|boolean',
            'is_sponsor_committee' => 'required|boolean',
            'is_telephoning_committee' => 'required|boolean'
        ];
    }
}
