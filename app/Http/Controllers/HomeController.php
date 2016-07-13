<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'fakeLogin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items      = Item::latest()->where('end_time', '>', Carbon::now())->paginate(24);
        $endedItems = Item::latest()->where('end_time', '<', Carbon::now())->paginate(24);

        return view('home', compact('items', 'endedItems'));
    }

    public function fakeLogin($id)
    {
        // so this will only accept 1 or 2 here, if anything else is passed through it treats it as if it were 1
        // this also needs a user with an ID of 2 and an id of 3 to work (user 1 you get to keep for yourself.)
        $id = in_array($id, [1, 2]) ? $id + 1 : 2;
        Auth::loginUsingId($id);

        return back();
    }
}
