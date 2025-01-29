<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class RotarianDentistInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $dentist;
    protected $rotarian;
    protected $invite_token;
    protected $year;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($dentist, $rotarian, $invite_token, $year)
    {
        $this->dentist = $dentist;
        $this->rotarian = $rotarian;
        $this->invite_token = $invite_token;
        $this->year = $year;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(8);
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('[dentist_name]', $this->dentist->firstname." ".$this->dentist->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[invite_token]', $this->invite_token, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[rotarian_name]', $this->rotarian->contact_salutation." ".$this->rotarian->firstname.' '.$this->rotarian->lastname, $autoreply->content);
        $subject = "Invitation by ".$this->rotarian->contact_salutation." ".$this->rotarian->firstname.' '.$this->rotarian->lastname.' for Dr. '.$this->dentist->firstname." ".$this->dentist->lastname.' to register for Brush-a-mania '.$this->year;
        // return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.school_invitation')->with([
        //     'school_name' => $this->school_name,
        //     'dentist' => $this->dentist,
        //     'invite_token' => $this->invite_token]);
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}