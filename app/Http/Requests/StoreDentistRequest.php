<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDentistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dentist_firstname' => 'required',
            'dentist_lastname' => 'required',
            'contact_salutation' => 'required',
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'email' => 'required|email|confirmed',
            'phone' => 'required',
            'school_name' => 'required_if:will_enter_school,'.true,
            'school_email' => 'required_if:will_enter_school,'.true,
            'presentation_date' => 'nullable|date',
            'will_pick_up_local' => 'required|boolean',
            'dental_society_name' => 'required_if:will_pick_up_local,'.true,
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
