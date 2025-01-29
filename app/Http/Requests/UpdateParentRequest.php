<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ChildParent;
use Auth;

class UpdateParentRequest extends FormRequest
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
        $parent = ChildParent::find(\Request::get('id'));
        $user_id = $parent->user->id;
        return [
            'contact_firstname' => 'required',
            'contact_lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id
        ];
    }
}
