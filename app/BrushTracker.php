<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrushTracker extends Model
{
    public function child() {
    	return $this->belongsTo('App\Child');
    }
}
