<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DentistRegistration extends Model
{
    public function dentist() {
    	return $this->belongsTo('App\Dentist');
    }
}
