<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\School;
use Auth;

class UpdateSchoolRequest extends FormRequest
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
        $school = School::find(\Request::get('id'));
        $user_id = $school->user->id;
        return [
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id,
            'contact_salutation' => 'required',
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'number_of_classes' => 'required|numeric|min:1',
            'number_of_students' => 'required|numeric|min:1'
        ];
    }
}
