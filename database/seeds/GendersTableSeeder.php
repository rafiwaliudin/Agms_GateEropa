<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->insert([
            [
                'name' => 'Laki - Laki',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Perempuan',
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
