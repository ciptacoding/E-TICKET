<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EntranceController extends Controller
{
    public function index(Request $request, Booking $booking) 
    {
        $query = Booking::query()->where('status', 'Paid');
        $entrances = $query->when($request->input('search'), function ($query, $search) {
            $query->where('full_name', 'like', '%' . $search . '%');
        })->paginate(6)->withQueryString();

        return Inertia::render('Dashboard/Entrance/Index',
        [
            'entrances' => $entrances,
            'filters' => $request->only(['search']),
        ]);
    }

    public function checkin(Request $request)
    {
        Booking::where('id', $request->id)->update(['status_entrance' => 'Check In']);
    }

    public function checkout(Request $request)
    {
        Booking::where('id', $request->id)->update(['status_entrance' => 'Check Out']);
    }

    public function blacklist(Request $request)
    {
        Booking::where('id', $request->id)->update(['status_entrance' => 'Blacklist']);

        $data = Booking::findOrFail($request->id);
        return Inertia::render('Dashboard/Entrance/Blacklist',
        [
            'data' => $data
        ]);
    }
}