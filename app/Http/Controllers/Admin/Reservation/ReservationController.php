<?php

namespace App\Http\Controllers\Admin\Reservation;



use App\Models\User;

use App\Models\Image;
use App\Models\Reservation;
use App\Models\Activity;
use App\Http\Helper;
use App\Models\SystemSetting;
use App\Models\Service;
use App\Models\Facility;
use App\Models\Requirement;
use App\Models\Location;
use App\Models\Room;
use App\Models\Attribute;



use Illuminate\Support\Str;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $settings;

    public function __construct()
    {	  
	  $this->settings =  SystemSetting::first();
    }

    /**
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     */
    public function index()
    {   
        $reservations = Reservation::orderBy('created_at','desc')->paginate(30);
        return view('admin.reservations.index',compact('reservations'));
    }

   /**
     * Show the form for creating a new resource.
     *
     * return \Illuminate\Http\Response
     */
    public function create()
    {
        User::canTakeAction(2);
        $services = Service::orderBy('name','asc')->get();
        $facilities = Facility::orderBy('name','asc')->get();
        $requirements = Requirement::orderBy('name','asc')->get();
        $locations = Location::parents()->get();
        $locations = Location::parents()->get();

        return view('admin.reservations.create',compact('services','locations','facilities','requirements'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $this->validate($request,[
            "apartment_name"  => "required",
            'address'=> "required",
            "city_id" => "required",
            
        ]);

        $reservation =  new Reservation();
        $reservation->name      = $request->apartment_name;
        $reservation->address   = $request->address;
        $reservation->city_id   = $request->city_id;
        $reservation->state_id  = Location::find($request->city_id)->parent_id;
        $reservation->image     = $request->image;
        $reservation->slug      = str_slug($request->apartment_name);
        $reservation->save();
        $reservation->facilities()->sync($request->facility_id);
        /**
         * Reservation Images
         */

        if (!empty($request->images) ) {
            $images =  $request->images;
            $images = array_filter($images);
            foreach ( $images as $variation_image) {
                $images = new Image(['image' => $variation_image]);
                $reservation->images()->save($images);
            }
        } 
    
       
        /***
         * General Facilities
        */

        
        /**
         * Rooms
         */

        $data = [];
        foreach ($request->room_name  as $key => $room) {
            $room = new Room;  
            $room_images = !empty($request->room_images[$key]) ? $request->room_images[$key] : [];
            $room->name = $request->room_name[$key];
            $room->price = $request->room_price[$key];
            $room->sale_price = $request->room_sale_price[$key];
            $room->slug = str_slug($request->room_name[$key]);
            $room->image = $request->room_image[$key];
            $room->available_from = Helper::getFormatedDate($request->room_avaiable_from[$key],true);
            //$room->sale_price_expires = Helper::getFormatedDate($request->room_sale_price_expires[$key],true);
            $room->reservation_id = $reservation->id;
            $room->save();
            if ( count( $room_images )  > 0) {
                $images = array_filter($room_images);
                foreach ( $images  as $image) {
                    $imgs= new Image(['image' => $image]);
                    $room->images()->save($imgs);
                }
            }
            
            foreach ($request->attribute_ids as $attributeId) {
                $data[1] = ['parent_id'=>null]; 
                $data[$attributeId] = ['parent_id'=>1];  
            }
            $room->attributes()->sync($data); 
        }
        /**
         * Rooms with have includes
        */


        (new Activity)->Log("Created a new reservation {$request->apartment_name}");
        return \Redirect::to('/admin/reservations');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function newRoom(Request $request){
        $counter = rand(1,500);
        $product_attributes =  Attribute::parents()->where('type','reservation')->orderBy('sort_order','asc')->get();
        return view('admin.reservations.variation',compact('counter','product_attributes'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        User::canTakeAction(5);
        $rules = array (
                '_token' => 'required' 
        );
        $validator = \Validator::make ( $request->all (), $rules );
        if (empty ( $request->selected )) {
            $validator->getMessageBag ()->add ( 'Selected', 'Nothing to Delete' );
            return \Redirect::back ()->withErrors ( $validator )->withInput ();
        }
        $count = count($request->selected);
        (new Activity)->Log("Deleted  {$count} Products");

        foreach ( $request->selected as $selected ){
            $delete = Reservation::find($selected);
            $delete->rooms()->delete();
            $delete->delete();
        }
        
        return redirect()->back();
    }
}
