<?php

use Illuminate\Database\Seeder;
use App\AutoreplyEmail;

class UpdateWinnersEmailName extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $autoreply = AutoreplyEmail::find(9);
        $autoreply->name = "Certificate of Achievement";
        $autoreply->save();
    }
}
