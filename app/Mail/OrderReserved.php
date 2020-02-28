<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Log;

class OrderReserved extends Mailable
{
    use Queueable, SerializesModels;

    /*
        content: {
            to: name,
            subject: text,
            body: text
        }
    */
    protected $content = null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    
        return $this->markdown('emails.orders.reserved')->subject($this->content['subject'])
                ->with([
                    'name' => $this->content['name'],
                    'subject' => $this->content['subject'],
                    'body' => $this->content['body'],
                ]);
    }
}
