<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingsPhotos extends Model
{
	public $table = 'listings_photos';
	public $timestamps = false;


    public function listings()
    {
    	return $this->belongsTo('App/Listings');
    }
}
