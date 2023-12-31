<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function index(Request $request)
    {   
       
        return Inertia::render('Frontend/Booking/Index', 
        [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register')
        ]);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'check_in' => 'date|required',
            'check_out' => 'date|required',
            'full_name' => 'min:2|max:100|required',
            'address' => 'required|max:100',
            'phone' => 'required|min:5',
            'gender' => 'required'
        ]);

        if($request->blacklist_id !== null)
        {
            return redirect()->route('booking.index')->with('message', 'Your account has been blacklist');
        }

        $checkIn = Booking::where('check_in', $request->check_in)->count();
        if($checkIn >= 100){
            return redirect()->route('booking.index')->with('message', 'Quota full! please  make sure the check-in date is still available on the Quota menu');
        }
        $strRandom = Str::random(30);
        // dd($strRandom);
        $booking = Booking::create([
            'order_update' => $strRandom,
            'user_id' => $request->user_id,
            'order_date' => date('Y-m-d'),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'full_name' => $request->full_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'total_price' => 25000,
            'status' => 'Unpaid',
        ]);  
        
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $booking->id,
                'gross_amount' => $booking->total_price,
            ),
            'customer_details' => array(
                'first_name' => $booking->full_name,
                'last_name' => $request->username,
                'email' => $request->email,
                'phone' => $booking->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $clientKey = config('midtrans.client_key');
        // dd($clientKey);
        return Inertia::render('Frontend/Booking/OrderDetail', 
        [
            'booking' => $booking,
            'snapToken' => $snapToken,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'clientKey' => $clientKey
        ]);
    }

    public function invoice($id)
    {
        $booking = Booking::find($id);
        return Inertia::render('Frontend/Booking/Invoice', 
        [
            'booking' => $booking,
            'bookingNumber' => $id,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function transactionHistory(Request $request){
        $userId = Auth::user()->id;
        // dd($userId);
        $booking = Booking::where('user_id', $userId)->paginate(6);
        // dd($booking);
        return Inertia::render('Frontend/Booking/History', [
            'bookings' => $booking,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }


    public function transactionPay(Request $request)
    {
        if($request->blacklist_id !== null)
        {
            return redirect()->route('booking.index')->with('message', 'Your account has been blacklist');
        }
        $id = $request->id;
        $booking = Booking::find($id);
        $strRandom = Str::random(30);
        $booking->update(['order_update' => $strRandom]);

        $checkIn = Booking::where('check_in', $booking->check_in)->count();
        // direvisi variable
        if($checkIn >= 100){
            return redirect()->route('booking.index')->with('message', 'Quota full! please  make sure the check-in date is still available on the Quota menu');
        }

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $booking->order_update,
                'gross_amount' => $booking->total_price,
            ),
            'customer_details' => array(
                'first_name' => $booking->full_name,
                'last_name' => $booking->username,
                'email' => $booking->email,
                'phone' => $booking->phone,
            ),
        ); 

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $clientKey = config('midtrans.client_key');
        // dd($clientKey);
        return Inertia::render('Frontend/Booking/OrderDetail', 
        [
            'booking' => $booking,
            'snapToken' => $snapToken,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'clientKey' => $clientKey
        ]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        if($hashed == $request->signature_key){
            if($request->transaction_status == 'capture' || 'settlement'){
                $booking = Booking::where('id', $request->order_id)->orWhere('order_update', $request->order_id);
                $booking->update(['status' => 'Paid']);
            }
        }

    }
}
