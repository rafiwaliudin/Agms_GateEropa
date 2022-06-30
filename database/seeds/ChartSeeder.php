<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('charts')->insert([
            [
                'nama_kawasan'=>'Sedayu City',
                'jumlah' => '10'
            ],
            [
                'nama_kawasan'=>'Taman Anggrek Residence',
                'jumlah' => '30'
            ],
            [
                'nama_kawasan'=>'Sedayu City Suites',
                'jumlah' => '20'
            ],
            [
                'nama_kawasan'=>'RES8-POM',
                'jumlah' => '60'
            ],
            [
                'nama_kawasan'=>'Puri Mansion Apartment',
                'jumlah' => '80'
            ]
            ]);
    }
}
