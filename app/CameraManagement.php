<?php

namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class CameraManagement extends Model
{
    public function AllData(){
        return DB::table('camera_managements')->get();
    }
}
