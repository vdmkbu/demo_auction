<?php

namespace App\Listeners;

use App\Events\LotReceivedNewBid;
use App\Mail\NewBid;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LotReceivedNewBid  $event
     * @return void
     */
    public function handle(LotReceivedNewBid $event)
    {
        $bid = $event->bid;
        $lot = $event->lot;
        $commission = $event->commission;

        User::where('role', User::ROLE_ADMIN)->get()
            ->each(function ($user) use ($bid, $lot, $commission) {
                Mail::to($user->email)->send(new NewBid($bid, $lot, $commission));
            });
    }
}
