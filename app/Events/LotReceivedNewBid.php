<?php

namespace App\Events;

use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LotReceivedNewBid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Bid $bid;
    public Lot $lot;
    public string $commission;

    public function __construct(Bid $bid, Lot $lot, string $commission)
    {
        $this->bid = $bid;
        $this->lot = $lot;
        $this->commission = $commission;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
