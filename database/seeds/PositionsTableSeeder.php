<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            [
                'name' => 'CEO',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'General Manager',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Director',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Lead',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Employee',
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
