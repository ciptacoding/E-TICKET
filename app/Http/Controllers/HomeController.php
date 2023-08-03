<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\Blacklist;
use App\Models\Suggestion;

class HomeController extends Controller
{
    public function index (Request $request)
    {
        $posts = Post::orderByDESC('date_post')->paginate(6);
        $suggestions = Suggestion::with('user')->get();
        $blacklists = Blacklist::query()->with('user');
        // dd($suggestions);
        return Inertia::render('Frontend/Home/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'posts' => $posts,
            'suggestions' => $suggestions,
            'blacklists' => $blacklists
                ->when($request->input('search'), function($query, $search){
                    $query->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
                })->orderByDESC('id')->paginate(6)->withQueryString(),
                'filters' => $request->only(['search'])
        ]);
    }
}
