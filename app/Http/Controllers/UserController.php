<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $blacklist = User::find(1)->blacklist;
        // dd($blacklist);
        return Inertia::render(
            'Dashboard/Users/Index',
            [
                'users' => User::query()
                ->when($request->input('search'), function($query, $search){
                    $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
                })->paginate(10)->withQueryString(),
                'filters' => $request->only(['search'])
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function disabled(Request $request)
    {
        User::where('id', $request->id)->update(['disabled' => 'true']);
        return redirect()->route('users.index')->with('success', 'User disabled successfully.');
    }
}
