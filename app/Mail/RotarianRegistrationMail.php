<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class RotarianRegistrationMail extends Mailable
{
    protected $rotarian;
    protected $year;
    protected $user;
    protected $user_added;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rotarian, $year, $user, $user_added)
    {
        $this->rotarian = $rotarian;
        $this->year = $year;
        $this->user = $user;
        $this->user_added = $user_added;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(3);
        if ($this->user_added) {
            $autoreply->content = $autoreply->content.'<br><p>
                You can login to this <a href="http://app.brushamania.ca/login">page</a> with the following credentials <br>
                Email: [email]<br>
                Password: [randstr]
            </p>';
            $autoreply->content = Str::replaceFirst('[email]', $this->user->email, $autoreply->content);
            $autoreply->content = Str::replaceFirst('[randstr]', $this->user->randstr, $autoreply->content);
        }
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('[rotarian]', $this->rotarian->contact_salutation.'. '.$this->rotarian->firstname." ".$this->rotarian->lastname, $autoreply->content);
        $subject = "Brush-a-mania Dentist registration confirmation for ".$this->rotarian->contact_salutation.'. '.$this->rotarian->firstname." ".$this->rotarian->lastname;
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}