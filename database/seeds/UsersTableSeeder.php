<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountAdministrator = User::create(
            [
                'name' => 'Admin ASG',
                'email' => 'asg_admin@alfabeta.co.id',
                'password' => bcrypt('sedayucitysukses'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $accountAdministrator->assignRole('Administrator');

        $accountUser = User::create(
            [
                'name' => 'User ASG',
                'email' => 'asg_user@alfabeta.co.id',
                'password' => bcrypt('sedayucitysukses'),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $accountUser->assignRole('User');
    }
}
