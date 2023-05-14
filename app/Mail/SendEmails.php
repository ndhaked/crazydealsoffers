<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmails extends Mailable
{
    use Queueable, SerializesModels;

    public $jobData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobData)
    {
        $this->jobData = $jobData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-template')
                    ->subject($this->jobData['subject'])
                    ->with('content', $this->jobData['content']);
    }
}
