<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRoast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $analysis;

    public function __construct($analysis)
    {
        $this->analysis = $analysis;
    }

    public function broadcastOn()
    {
        return new Channel('roasts');
    }

    public function broadcastAs()
    {
        return 'new-roast';
    }
}