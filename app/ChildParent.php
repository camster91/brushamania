<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildParent extends Model
{
    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function children() {
    	return $this->hasMany('App\Child');
    }

    public function parent_registrations() {
    	return $this->hasMany('App\ParentRegistration');
    }
}
