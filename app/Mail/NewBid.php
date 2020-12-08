<?php

namespace App\Mail;

use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBid extends Mailable
{
    use Queueable, SerializesModels;

    public Bid $bid;
    public Lot $lot;
    public string $commission;

    public function __construct(Bid $bid, Lot $lot, string $commission)
    {
        $this->bid = $bid;
        $this->lot = $lot;
        $this->commission = $commission;
    }


    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("Новая ставка")
            ->markdown('mails.new_bid');
    }
}
