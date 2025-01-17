<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Presentation extends Model
{
    public function dentist() {
    	return $this->hasOne('App\Dentist');
    }

    public function school() {
    	return $this->hasOne('App\School');
    }

    public function rotarian() {
    	return $this->hasOne('App\Rotarian');
    }

    public function guests() {
        return $this->hasMany('App\Presentation');
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('F d, Y');
    }
}
