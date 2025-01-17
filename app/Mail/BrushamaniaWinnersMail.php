<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AutoreplyEmail;
use Illuminate\Support\Str;

class BrushamaniaWinnersMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $file)
    {
        $this->subject = $subject;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(9);
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = str_replace('<pre>', '<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        return $this->subject($this->subject)->view('mail.winners')->with([
            'subject' => $this->subject,
            'content' => $autoreply->content.' '.$this->file
        ])->attach($this->file, [
            'as' => 'certificate.pdf',
            'mime' => 'application/pdf'
        ])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}