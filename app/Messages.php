<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
	public $table = 'messages';
	protected $hidden = ['password'];

    public function bookings()
    {
    	return $this->belongsTo('App\Bookings');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
