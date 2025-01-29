<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
            'contact_salutation' => 'required',
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'email' => 'required|email|confirmed',
            'number_of_classes' => 'required|numeric|min:1',
            'number_of_students' => 'required|numeric|min:1',
            'presentation_date' => 'nullable|date'
        ];
    }
}
