<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BidReceived extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $item_id;
    public $currentTotal;
    public $timestamp;
    public $highBidder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item_id, $currentTotal, $timestamp, $highBidder)
    {
        //
        $this->item_id      = $item_id;
        $this->currentTotal = $currentTotal;
        $this->timestamp    = $timestamp;
        $this->highBidder   = $highBidder;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['bids-channel'];
    }
}
