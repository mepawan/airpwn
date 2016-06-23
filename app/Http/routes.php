<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){
	return view('index');
});

Route::post('auth/facebook', 'Auth\AuthController@facebook');
Route::post('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::post('auth/signup', 'Auth\AuthController@signup');


// API

Route::group(array('prefix' => 'api/v1'), function()
{
	Route::resource('listings', 'ListingsController');
	Route::resource('bookings', 'BookingsController');
	Route::resource('messages', 'MessagesController');
	// user profile
	Route::get('user', 'UserController@getUser');
	Route::get('user/listings', 'UserController@getListings');
	Route::put('user', 'UserController@updateUser');
	Route::get('user/reservations', 'UserController@getReservations');
	Route::get('user/trips', 'UserController@getTrips');
	Route::post('payments', 'UserController@charge');
	Route::post('listings/block/{id}', 'ListingsController@block');
	// other users
	Route::get('user/{id}', 'UserController@showUser');

	Route::get('search/location/{location}/checkin/{checkin}/checkout/{checkout}', 'ListingsController@search');
});
