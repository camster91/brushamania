<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Test Mail";
        $this->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $this->content);
        $this->content = str_replace('</pre>', '</p>', $this->content);
        $this->content = str_replace('&lt;br&gt;', '<br>', $this->content);
        $this->content = str_replace('&lt;strong&gt;', '<strong>', $this->content);
        $this->content = str_replace('&lt;/strong&gt;', '</strong>', $this->content);
        // $this->content = Str::replaceFirst('<pre class="ql-syntax" spellcheck="false">', '<p>', $this->content);
        // $this->content = Str::replaceFirst('</pre>', '</p>', $this->content);
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.testmail')->with([
            'content' => $this->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}