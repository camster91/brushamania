<?php

use Illuminate\Database\Seeder;
use App\AutoreplyEmail;

class RotarianAutoreplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = new AutoreplyEmail;
		$email->name = "Rotarian Registration Confirmation";
		$email->url_slug = "rotarian-registration-confirmation";
		$email->content = '<p>Thank you Dr. [rotarian_name] for participating in Brush-a-mania 2022!</p>';
        $email->save();
    }
}
