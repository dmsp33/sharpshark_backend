<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alerts_to_send;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($alerts_to_send , $user)
    {
        $this->alerts_to_send = $alerts_to_send;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.firstv');
    }
}
