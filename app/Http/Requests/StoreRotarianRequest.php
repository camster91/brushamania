<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRotarianRequest extends FormRequest
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
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'email' => 'required|email',
            'club_name' => 'required',
            'is_organizing_committee' => 'required|boolean',
            'is_lootbag_committee' => 'required|boolean',
            'is_pr_media_committee' => 'required|boolean',
            'is_sponsor_committee' => 'required|boolean',
            'is_telephoning_committee' => 'required|boolean',
        ];
    }
}
