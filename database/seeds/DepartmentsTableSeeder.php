<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'name' => 'Technology',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Finance',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Managerial',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Human Resources',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Business',
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
