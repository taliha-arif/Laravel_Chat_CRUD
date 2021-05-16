<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class forgetpasswordmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //setting the data which have to send to forgotten user
        $this->forgetpasswordmail_mail_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('talihaarif13@gmail.com', 'Taliha Arif')->subject('Reset Password!')->view('mail.forgotpassword', ['mail_data' => $this->forgetpasswordmail_mail_data]);
    }
}
