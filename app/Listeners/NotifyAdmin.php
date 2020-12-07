<?php

namespace App\Listeners;

use App\Events\LotReceivedNewBid;
use App\Mail\NewBid;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        $data = $event->data;

        User::where('role', User::ROLE_ADMIN)->get()
            ->each(function ($user) use ($data) {
                Mail::to($user->email)->send(new NewBid($data));
            });
    }
}
