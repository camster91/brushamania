<?php

use Illuminate\Database\Seeder;

class ResetBrushtrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brush_trackers')->delete();
    }
}
