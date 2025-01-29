<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildRegistration extends Model
{
    public function child() {
    	return $this->belongsTo('App\Child');
    }
}
