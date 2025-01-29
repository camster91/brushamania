<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'province', 'postal_code', 'phone', 'url_slug'
    ];

    // protected $appends = ['school_slug'];

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function presentation() {
    	return $this->belongsTo('App\Presentation');
    }

    public function school_registrations() {
    	return $this->hasMany('App\SchoolRegistration');
    }

}
