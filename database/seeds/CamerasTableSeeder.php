<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CamerasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DB::table('cameras')->insert([ */
        /*     [ */
        /*         'name' => 'Camera Gate 1 In', */
        /*         'camera_type_id' => 2, */
        /*         'ws_port' => 891, */
        /*         'created_at' => Carbon::now() */
        /*     ], */
        /*     [ */
        /*         'name' => 'Camera Gate 1 Out', */
        /*         'camera_type_id' => 2, */
        /*         'ws_port' => 892, */
        /*         'created_at' => Carbon::now() */
        /*     ], */
        /*     [ */
        /*         'name' => 'Camera Gate 2 In', */
        /*         'camera_type_id' => 2, */
        /*         'ws_port' => 893, */
        /*         'created_at' => Carbon::now() */
        /*     ], */
        /*     [ */
        /*         'name' => 'Camera Gate 2 Out', */
        /*         'camera_type_id' => 2, */
        /*         'ws_port' => 894, */
        /*         'created_at' => Carbon::now() */
        /*     ], */
        /*     [ */
        /*         'name' => 'Camera Attendance', */
        /*         'camera_type_id' => 1, */
        /*         'ws_port' => 881, */
        /*         'created_at' => Carbon::now() */
        /*     ], */
        /* ]); */
        DB::table('residential_gates')->insert([
            [
                'name' => 'Gate 1',
                'cluster_id' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Gate 2',
                'cluster_id' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Gate In',
                'cluster_id' => 62,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Springwood Resident's Entrance Gate",
                'cluster_id' => 64,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Springwood Resident's Exit Gate",
                'cluster_id' => 64,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Springwood Visitor's Entrance Gate",
                'cluster_id' => 64,
                'created_at' => Carbon::now()
            ],
            [
                'name' => "Springwood Visitor's Exit Gate",
                'cluster_id' => 64,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Europe Entrance Gate',
                'cluster_id' => 63,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Europe Exit Gate',
                'cluster_id' => 63,
                'created_at' => Carbon::now()
            ],
        ]);
        
    }
}
