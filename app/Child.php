<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    public function child_parent() {
    	return $this->belongsTo('App\ChildParent');
    }

	public function brush_trackers() {
		return $this->hasMany('App\BrushTracker');
	}

	public function child_registrations() {
    	return $this->hasMany('App\ChildRegistration');
    }
}
