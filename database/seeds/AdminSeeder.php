<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = "cameronadmin@ashbi.ca";
        $user->password = bcrypt('12345678');
        $user->role = 'admin';
        $user->firstname = "Cameron";
        $user->lastname = "Ashbi";
        $user->save();
    }
}
