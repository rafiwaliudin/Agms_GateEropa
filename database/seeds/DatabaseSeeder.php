<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ReligionsTableSeeder::class);
        $this->call(BloodTypesTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(CameraTypesTableSeeder::class);
        $this->call(CamerasTableSeeder::class);
        $this->call(ObjectVehiclesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
    }
}
