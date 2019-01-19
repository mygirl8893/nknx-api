<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NodeStuckMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $nodes;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$nodes)
    {
        $this->user = $user;
        $this->nodes = $nodes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.nodeStuck');
    }
}
