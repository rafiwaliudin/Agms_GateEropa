<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ObjectVehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('object_vehicles')->insert([
            [
                'name' => 'Mobil',
                'code' => 'car',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Orang',
                'code' => 'person',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Motor',
                'code' => 'motorbike',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Truk',
                'code' => 'truck',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Bus',
                'code' => 'bus',
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
