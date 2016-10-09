<?php

use App\Faculty;
use Illuminate\Database\Seeder;

class FacultiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Faculty::KEYS as $key => $id) {
            Faculty::firstOrNew([
                'id' => $id,
                'key' => $key
            ])->save();
        }
    }
}
