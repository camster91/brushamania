<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class SchoolConfirmedForDentistMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $dentist;
    protected $school;
    protected $school_user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dentist, $school, $school_user)
    {
        $this->dentist = $dentist;
        $this->school = $school;
        $this->school_user = $school_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(5);
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_name]', $this->dentist->firstname." ".$this->dentist->lastname, $autoreply->content);
        $autoreply->content = Str::replaceArray('[school_name]', [$this->school->name, $this->school->name], $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_address]', $this->school->address, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_city]', $this->school->city.", ".$this->school->province, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_postal_code]', $this->school->postal_code, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_phone]', $this->school->phone, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_contact]', $this->school->contact_salutation." ".$this->school_user->firstname." ".$this->school_user->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_email]', $this->school_user->email, $autoreply->content);
        $subject = "Brush-a-mania school confirmed for Dr. ".$this->dentist->firstname." ".$this->dentist->lastname;
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}