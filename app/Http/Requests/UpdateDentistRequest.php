<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Dentist;
use Auth;

class UpdateDentistRequest extends FormRequest
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
        $dentist = Dentist::find(\Request::get('id'));
        $user_id = $dentist->user->id;
        return [
            'dentist_firstname' => 'required',
            'dentist_lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id,
            'contact_salutation' => 'required',
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'phone' => 'required',
            'postcard' => 'nullable|numeric',
            'is_organizing_committee' => 'required|boolean',
            'is_lootbag_committee' => 'required|boolean',
            'is_pr_media_committee' => 'required|boolean',
            'is_sponsor_committee' => 'required|boolean',
            'is_telephoning_committee' => 'required|boolean',
            'add_clinic_to_dentistfind' => 'required|boolean'
        ];
    }
}
