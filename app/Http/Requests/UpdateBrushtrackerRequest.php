<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateBrushtrackerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->role == 'parent') {
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
        return [
            'morning_brush' => 'required|boolean',
            'morning_floss' => 'required|boolean',
            'lunchtime_brush' => 'required|boolean',
            'lunchtime_floss' => 'required|boolean',
            'night_brush' => 'required|boolean',
            'night_floss' => 'required|boolean',
            'bonus_brush' => 'required|boolean',
            'bonus_floss' => 'required|boolean'
        ];
    }
}
