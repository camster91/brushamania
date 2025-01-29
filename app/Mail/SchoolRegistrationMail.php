<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use App\AutoreplyEmail;

class SchoolRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $school;
    protected $user;
    protected $presentation;
    protected $user_added;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($school, $user, $presentation, $user_added)
    {
        $this->school = $school;
        $this->user = $user;
        $this->presentation = $presentation;
        $this->user_added = $user_added;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $presentation_date = "";
        $presentation_time = "";
        if ($this->presentation->presentation_date != "" && $this->presentation->presentation_date != null && !empty($this->presentation->presentation_date)) {
            $date = date_create($this->presentation->presentation_date);
            $presentation_date = date_format($date, "F d, Y");
            $presentation_time = date_format($date, "g:i A");
        }
        $autoreply = AutoreplyEmail::find(2);
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
        $autoreply->content = Str::replaceArray('[school_name]', [$this->school->name, $this->school->name, $this->school->name], $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_address]', $this->school->address, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_city]', $this->school->city.', '.$this->school->province, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_postal_code]', $this->school->postal_code, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_phone]', $this->school->phone, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[school_contact]', $this->school->contact_salutation.' '.$this->user->firstname.' '.$this->user->lastname, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[user_email]', $this->user->email, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[requested_dentist]', $this->school->requested_dentist, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[number_of_classes]', $this->presentation->number_of_classes, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[number_of_students]', $this->presentation->number_of_students, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[presentation_date]', $presentation_date, $autoreply->content);
        $autoreply->content = Str::replaceFirst('[presentation_time]', $presentation_time, $autoreply->content);
        $subject = "Brush-a-mania School registration confirmation for ".$this->school->name;
        // return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.school_registration')->with([
        //     'school' => $this->school,
        //     'user' => $this->user,
        //     'presentation' => $this->presentation,
        //     'presentation_date' => $presentation_date]);
        return $this->from('info@brushamania.ca', 'Brush-a-mania')->subject($subject)->view('mail.autoreply')->with([
            'content' => $autoreply->content])

            ->bcc('jennifer.boyd@hotmail.com')
            ->bcc('dr.chouljian@scarboroughnorthdental.ca')
            ->bcc('abbasumaru44@gmail.com');
    }
}