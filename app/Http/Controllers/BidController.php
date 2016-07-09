<?php

namespace App\Http\Controllers;

use App\Events\BidReceived;
use App\Models\Bid;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class BidController extends Controller
{

    public function makeBid(Request $request, Item $item)
    {
        Bid::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'amount'  => $request->amount,
        ]);

        $currentBid = $item->currentBid(); // this should be *this* bid

        event(new BidReceived($item->id, $currentBid->amount, Carbon::now()->timestamp, $currentBid->user->name));
    }

}
