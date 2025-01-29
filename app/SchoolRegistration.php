<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolRegistration extends Model
{
    public function school() {
    	return $this->belongsTo('App\School');
    }
}
