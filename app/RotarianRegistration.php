<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RotarianRegistration extends Model
{
    public function rotarian() {
    	return $this->belongsTo('App\Rotarian');
    }
}
