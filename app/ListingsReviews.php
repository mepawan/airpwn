<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingsReviews extends Model
{

	public $table = 'listing_reviews';
	
    public function listings()
    {
    	return $this->belongsTo('App\Listings');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
