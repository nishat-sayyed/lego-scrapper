<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LegoAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $lego;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lego)
    {
        $this->lego = $lego;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.lego-added');
    }
}
