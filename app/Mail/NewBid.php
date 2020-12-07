<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBid extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject("Новая ставка")
            ->markdown('mails.new_bid');
    }
}
