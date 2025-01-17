<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class ParentRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $parent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $autoreply = AutoreplyEmail::find(1);
        $autoreply->content = str_replace('<pre class="ql-syntax" spellcheck="false">','<p>', $autoreply->content);
        $autoreply->content = str_replace('</pre>', '</p>', $autoreply->content);
        $autoreply->content = str_replace('&lt;br&gt;', '<br>', $autoreply->content);
        $autoreply->content = str_replace('&lt;strong&gt;', '<strong>', $autoreply->content);
        $autoreply->content = str_replace('&lt;/strong&gt;', '</strong>', $autoreply->content);
        $autoreply->content = Str::replaceFirst('[parent_name]', $this->parent->firstname." ".$this->parent->lastname, $autoreply->content);
        $subject = "Brush-a-mania Parent registration confirmation for ".$this->parent->firstname." ".$this->parent->lastname;
        // return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.parent_registration')->with([
        //     'parent' => $this->parent]);
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}