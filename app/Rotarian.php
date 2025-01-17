<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rotarian extends Model
{
    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function presentation() {
    	return $this->belongsTo('App\Presentation');
    }

    public function rotarian_registrations() {
    	return $this->hasMany('App\RotarianRegistration');
    }
}
