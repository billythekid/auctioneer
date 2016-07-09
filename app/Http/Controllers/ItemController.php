<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::where('active', 1)->paginate(24);

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required',
            'description' => 'required',
            'price'       => 'required|integer',
            'duration'    => 'required|integer|min:1|max:28',
        ]);
        $item              = Item::createFromTitle($request->title);
        $item->user_id     = $request->user()->id;
        $item->description = $request->description;
        $item->price       = $request->price;
        $item->end_time    = Carbon::now()->addDays($request->duration);
        $item->save();
        $item->categories()->sync($request->categories);

        if ($request->ajax())
        {
            return response()->json(['item' => $item]);
        }

        session()->flash('success', "The item, {$item->title}, was successfully listed.");

        return redirect()->route('item.show', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $this->authorize($item);

        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
