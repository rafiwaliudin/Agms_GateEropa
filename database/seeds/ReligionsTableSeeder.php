<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReligionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religions')->insert([
            [
                'name' => 'Islam',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Kristen',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Katolik',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Budha',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Hindu',
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
