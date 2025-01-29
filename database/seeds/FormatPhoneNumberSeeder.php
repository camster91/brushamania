<?php

use Illuminate\Database\Seeder;
use App\ChildParent;
use Propaganistas\LaravelPhone\PhoneNumber;


class FormatPhoneNumberSeeder extends Seeder
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
            $parent->formatted_phone = PhoneNumber::make($parent->phone, 'CA');
            $parent->save();
        }
    }
}
