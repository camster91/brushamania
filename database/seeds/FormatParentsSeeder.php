<?php

use Illuminate\Database\Seeder;
use App\ChildParent;
use App\User;

class FormatParentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parents = ChildParent::get();
        foreach($parents as $parent) {
            $parent->firstname = ucwords(strtolower($parent->firstname));
            $parent->lastname = ucwords(strtolower($parent->lastname));
            $parent->save();
            $user = User::find($parent->user_id);
            $user->email = strtolower($user->email);
            $user->save();
        }
    }
}
