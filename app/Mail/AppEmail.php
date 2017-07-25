<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message_text;
    public $message_subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message,$subject)
    {
        $this->message_text = $message;
        $this->message_subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->message_subject)->view('emails.general')->with([
            'message_text'=>$this->message_text
        ]);
    }
}
