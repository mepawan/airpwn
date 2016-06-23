<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function reviews()
    {
    	return $this->hasMany('App\ListingsReviews');
    }

    public function images()
    {
    	return $this->hasMany('App\ListingsPhotos');
    }
}
