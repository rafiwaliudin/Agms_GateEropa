<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CameraTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('camera_types')->insert([
            [
                'name' => 'recognize',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'counting',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'masking',
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
