<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Item;
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
        $items = Item::latest()->paginate(24);

        return view('home', compact('items'));
    }

    public function fakeLogin($id)
    {
        $id = in_array($id,[1,2]) ? $id + 1 : 2;
        Auth::loginUsingId($id);
        return back();
    }
}
