<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{

	protected $hidden = ['password'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function messages()
    {
    	return $this->hasMany('App\Messages');
    }
}
