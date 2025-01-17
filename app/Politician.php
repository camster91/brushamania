<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Politician extends Model
{
    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'url_slug', 'title', 'constituency', 'city', 'province'
    ];
}
