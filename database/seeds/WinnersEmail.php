<?php

use Illuminate\Database\Seeder;
use App\AutoreplyEmail;

class WinnersEmail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email1 = new AutoreplyEmail;
		$email1->name = "Winners Email";
		$email1->url_slug = "winners-email";
        $email1->content = '<p>Dear Parents:</p>
        <p>We would like to congratulate your child on completing 100 brushes and flosses. We hope they continue to practice good oral hygiene. As a reward for their effort, their Brush-a-mania certificate of achievement is attached and their name will be entered into our draw for a chance to win an XBox or one of fifty tablets.</p>
        <p>We will require the following information from you. Please click on the <a href="http://brushamania.ca/winners-form/" target="_blank">link</a> and fill out the form or you can copy the link:&nbsp;<a title="http://brushamania.ca/winners-form/" href="http://brushamania.ca/winners-form/">http://brushamania.ca/winners-form/</a>.</p>
        <p>Winners will be notified after June 7.  Prizes will be sent to your child\'s school for presentation.</p>
        <p>We look forward to your participation next year.</p>
        <p>Sincerely yours for better dental health,</p>
        <p>Dr. Raffy Chouljian</p>
        <p>Brush-a-mania Chair</p>
        ';
		$email1->save();
    }
}
