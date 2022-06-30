<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BloodTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blood_types')->insert([
            [
                'name' => 'AB',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'A',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'B',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'O',
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
