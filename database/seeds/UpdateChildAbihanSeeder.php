<?php

use Illuminate\Database\Seeder;
use App\Child;
use App\BrushTracker;

class UpdateChildAbihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $child = Child::find(568);
        $child->url_slug = 'child-abiha-imran-old';
        $child->save();
        BrushTracker::where('child_id', 568)->where('year', 2022)->update(['child_id' => 1267]);
    }
}
