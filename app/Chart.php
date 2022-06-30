<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chart extends Model
{
    public function AllData(){
        return DB::table('charts')->get();
    }

    
}
