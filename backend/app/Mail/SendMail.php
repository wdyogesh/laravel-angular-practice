<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parameters)
    {
        $this->data = $parameters;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->data['template_name'])->with($this->data['template_data'])
            ->from($this->data['from'], $this->data['first_name'])
            // ->cc($address, $name)
            // ->bcc($address, $name)
            // ->replyTo($address, $name)
            ->subject($this->data['subject']);
    }
}
