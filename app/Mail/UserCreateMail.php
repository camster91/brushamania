<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $randstr;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($randstr, $email)
    {
        $this->randstr = $randstr;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Brushamania Signup";

        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.usercreate')->with([
            'randstr' => $this->randstr,
            'email' => $this->email])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}