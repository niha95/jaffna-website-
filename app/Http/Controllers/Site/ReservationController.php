<?php
namespace App\Http\Controllers\Site;

use App\Models\HotelReservation;
use App\Models\FlightReservation;
use App\Models\TripReservation;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class ReservationController extends SiteController {

    public function hotelReservation(Request $request)
    {

        $input = $request->only('hotel_name', 'city', 'check_in',
            'check_out', 'number_of_rooms', 'type_of_rooms' ,'name','phone_number','email');

        $message = new HotelReservation($input);

        if ($message->save()) {
            flashMessage(trans('messages.message_sent_successfully'));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function flightReservation(Request $request)
    {

        $input = $request->only('Product_name', 'Price', 'Quantity',
            'date', 'hours', 'Place','name','phone_number','email');

        $message = new FlightReservation($input);

        if ($message->save()) {
            flashMessage(trans('messages.message_sent_successfully'));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }


    public function hotelReservationRedirection(Request $request)
    {

        $input = $request->only('hotel_name', 'city', 'check_in',
            'check_out', 'number_of_rooms', 'type_of_rooms' ,'name','phone_number','email');

        $message = new HotelReservation($input);

       return view('site.book_hotel', [
            'message' => $message,
        ]);
    }

    public function flightReservationRedirection(Request $request)
    {

        $input = $request->only('Product_name', 'Price', 'Quantity',
            'date', 'hours', 'Place','name','phone_number','email');

        $message = new FlightReservation($input);

        return view('site.book_flight', [
            'message' => $message,
        ]);
    }

     public function tripReservation(Request $request)
    {

        $input = $request->only('name','phone_number','email','trip');

        $message = new TripReservation($input);

        if ($message->save()) {
            flashMessage(trans('messages.message_sent_successfully'));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

     public function showBookHotel()
    {
       return view('site.book_hotel');
    }
       public function showBookFlight()
    {
       return view('site.book_flight');
    }

}