<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    /**
     * The person the message is from.
     *
     * @var array
     */
    public $from = [];

    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    public $to = [];

    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    public $cc = [];

    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    public $bcc = [];

    /**
     * The "reply to" recipients of the message.
     *
     * @var array
     */
    public $replyTo = [];

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject = '';

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $user)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user)
            ->view('emails.new_user');
    }
}
