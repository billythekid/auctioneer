<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserSignedUp extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $username;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function  __construct($username)
    {
        $this->username = $username;
    }


    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['test-channel'];
    }
}
