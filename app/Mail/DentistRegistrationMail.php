<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class DentistRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $dentist;
    protected $school;
    protected $year;
    protected $user;
    protected $user_added;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dentist, $school, $year, $user, $user_added)
    {
        $this->dentist = $dentist;
        $this->school = $school;
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
        $committees = "<ul>";
        if ($this->dentist->is_organizing_committee) {
            $committees = $committees."<li>Organizing Committee</li>";
        }
        if ($this->dentist->is_lootbag_committee) {
            $committees = $committees."<li>Lootbag Committee</li>";
        }
        if ($this->dentist->is_pr_media_committee) {
            $committees = $committees."<li>PR Media Committee</li>";
        }
        if ($this->dentist->is_sponsor_committee) {
            $committees = $committees."<li>Sponsor Committee</li>";
        }
        if ($this->dentist->is_telephoning_committee) {
            $committees = $committees."<li>Telephoning Committee</li>";
        }
        $committees = $committees."</ul>";

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
        $autoreply->content = Str::replaceFirst('[dentist_name]', $this->dentist->firstname." ".$this->dentist->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_name]', $this->school, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[committees]', $committees, $autoreply->content);
        $subject = "Brush-a-mania Dentist registration confirmation for Dr. ".$this->dentist->firstname." ".$this->dentist->lastname;


        // return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.dentist_registration')->with([
        //     'school' => $this->school,
        //     'dentist' => $this->dentist,
        //     'year' => $this->year]);
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])
            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}