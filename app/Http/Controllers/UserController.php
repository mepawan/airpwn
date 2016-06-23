<?php

namespace App\Http\Controllers;

use App\User;
use App\Listings;
use App\Bookings;

use Stripe\Stripe;
use DB;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Firebase\JWT\JWT;

class UserController extends Controller
{

	protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, \Config::get('app.token_secret'));
    }

	public function getUser(Request $request)
	{
		$user = User::find(Auth::user()->id);
		return response()->json($user);
	}

	public function showUser($id)
	{
		$user = User::find($id);
		return response()->json($user);
	}

	public function getListings()
	{	

		$listings = Listings::where('user_id', '=', Auth::user()->id)->get();

		return response()->json($listings);
	}

	public function getReservations()
	{
		$reservations = DB::table('bookings')
						->where('host_id', '=', Auth::user()->id)
						->join('users', 'bookings.user_id', '=', 'users.id')
						->join('listings', 'bookings.listing_id', '=', 'listings.id')
						->select('bookings.status', 'users.name', 
							'bookings.checkin', 'bookings.checkout',
							'bookings.id', 'bookings.user_id', 'listings.title', 'listings.address')
						->get();

		return response()->json($reservations);
	}

	public function getTrips()
	{
		$trips = DB::table('bookings')
						->where('bookings.user_id', '=', Auth::user()->id)
						->join('users', 'bookings.host_id', '=', 'users.id')
						->join('listings', 'bookings.listing_id', '=', 'listings.id')
						->select('bookings.status', 'users.name', 
							'bookings.checkin', 'bookings.checkout',
							'bookings.id', 'bookings.user_id', 'listings.title', 'listings.address')
						->get();

		return response()->json($trips);
	}

	public function updateUser(Request $request)
	{
		$user = User::find(Auth::user()->id);
		$user->name  	= $request->input('name');
		$user->email 	= $request->input('email');
		$user->gender 	= $request->input('gender');
		$user->avatar	= $request->input('avatar');
		$user->save();
	}

	public function createCustomer(Request $request)
	{
		\Stripe\Stripe::setApiKey(env('STRIPE_API_SECRET'));

		// Create a Recipient
		$recipient = \Stripe\Recipient::create(array(
		  "name" => 'John Doe',
		  "type" => "individual",
		  "bank_account" => $request->input('token'),
		  "email" => $request->input('email'))
		);
	}

	public function charge(Request $request)
	{
		$token = $request->input('token');
		$amount = $request->input('amount') * 100;
		$booking_id     = $request->input('booking_id');

		$user = Auth::user();
		$user->charge($amount, [
			'source' => $token,
			'receipt_email' => $user->email
		]);

		$bookings = Bookings::find($booking_id);
		$bookings->status = 'Booked';
		$bookings->save();
	}
	
}