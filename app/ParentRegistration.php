<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ParentRegistration extends Model
{
    public function getFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = Str::title($value);
    }

    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = Str::title($value);
    }

    public function getLastnameAttribute($value)
    {
        $this->attributes['lastname'] = Str::title($value);
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = Str::title($value);
    }

    public function child_parent() {
    	return $this->belongsTo('App\ChildParent');
    }
}
