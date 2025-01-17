<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    public function presentation() {
    	return $this->belongsTo('App\Presentation');
    }
}
