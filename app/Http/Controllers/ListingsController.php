<?php

namespace App\Http\Controllers;

use App\Listings;
use App\Bookings;
use App\ListingsReviews;
use App\ListingsPhotos;
use App\User;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $listings = Listings::orderBy('created_at', 'DESC')->simplePaginate(10);
        return response()->json($listings);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
         
        $listings = new Listings;
        $listings->title        =         $request->input('title');
        $listings->price_cents  =    $request->input('price_cents');
        $listings->summary      =       $request->input('summary');
        $listings->city         =          $request->input('city');
        $listings->home_type    =      $request->input('home_type');
        $listings->city         =           $request->input('city');
        $listings->address      =        $request->input('address');
        $listings->user_id      =       Auth::user()->id;
        $listings->status       =       'Listed';
        $listings->save();

        foreach($request['images'] as $image){
            $listings_photos = new ListingsPhotos;
            $listings_photos->url            = $image['url'];
            $listings_photos->listings_id    = $listings->id;
            $listings_photos->save();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $listing = Listings::find($id);
        $listing->user;
        $listing->reviews;
        $listing->images;

        return response()->json($listing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $listings = Listings::find($id);
        $listings->title        =         $request->input('title');
        $listings->price_cents  =    $request->input('price_cents');
        $listings->summary      =       $request->input('summary');
        $listings->city         =          $request->input('city');
        $listings->home_type    =      $request->input('home_type');
        $listings->city         =           $request->input('city');
        $listings->address      =        $request->input('address');
        $listings->status       =         $request->input('status');
        $listings->save();


        $listings_photos = ListingsPhotos::where('listings_id', '=', $id);
        $listings_photos->forceDelete();

        foreach($request['images'] as $image){
            $listings_photos = new ListingsPhotos;
            $listings_photos->url           = $image['url'];
            $listings_photos->listings_id    = $listings->id;
            $listings_photos->save();
        }
    }

    public function block($id, Request $request)
    {
        $bookings = new Bookings;
        $bookings->checkin      = strtotime($request->input('checkin')) * 1000;
        $bookings->checkout     = strtotime($request->input('checkout')) * 1000;
        $bookings->user_id      = Auth::user()->id;
        $bookings->listing_id   = $id;
        $bookings->status       = 'Blocked';
        $bookings->save();
    }

    /**
     * Remove the specified resource from storage. 1440651600000 < 1440910800000
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function search($location, $checkin, $checkout)
    {

        $booked = DB::table('bookings')
            ->where('checkin', '<=', $checkout)
            ->where('checkout', '>=', $checkin)
            ->lists('listing_id');

        $listings = DB::table('listings')
            ->where('listings.city', 'LIKE', '%'.$location.'%')
            ->join('listings_photos', 'listings_photos.listings_id', '=', 'listings.id')
            ->where('listings.status', '=', 'Listed')
            ->groupBy('listings.id')
            ->whereNotIn('listings.id', $booked)
            ->get();

       return response()->json($listings);

    }
}
