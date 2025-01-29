<?php

use Illuminate\Database\Seeder;

use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $user = new User;
        $user->email = "earl@dentistfind.com";
        $user->password = bcrypt('password');
        $user->role = 'admin';
        $user->firstname = "Earl";
        $user->lastname = "Bulasa";
        $user->save();
    }
}
