<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class DentistConfirmedForSchoolMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $school_name;
    protected $dentist;
    protected $dentist_user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($school_name, $dentist, $dentist_user)
    {
        $this->school_name = $school_name;
        $this->dentist = $dentist;
        $this->dentist_user = $dentist_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(4);
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('<pre>', '<p>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('</pre>', '</p>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_name]', $this->school_name, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_name]', $this->dentist->firstname." ".$this->dentist->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_clinic]', $this->dentist->clinic_name, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_contact_name]', $this->dentist->contact_salutation." ".$this->dentist_user->firstname." ".$this->dentist_user->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_phone]', $this->dentist->phone, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_email]', $this->dentist_user->email, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_website]', $this->dentist->website, $autoreply->content);
        $subject = "A dentist has been assigned to ".$this->school_name." for Brush-a-mania";
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}