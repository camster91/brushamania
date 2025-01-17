<?php

use Illuminate\Database\Seeder;

class DeleteJobs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->delete();
        DB::table('failed_jobs')->delete();
    }
}
