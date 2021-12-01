<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $email, $password)
    {
        $this->first_name = $first_name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome-email', [
            'first_name'=>$this->first_name, 
            'email'=>$this->email, 
            'password'=>$this->password
        ])->to($this->email)->subject('Your Account Created on ' . env('APP_NAME'));;
    }
}
