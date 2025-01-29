<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
	protected $fillable = [
        'firstname', 'lastname', 'phone', 'url_slug', 'last_registration_year'
    ];
    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function presentation() {
    	return $this->belongsTo('App\Presentation');
    }

    public function dentist_registrations() {
    	return $this->hasMany('App\DentistRegistration');
    }
}
