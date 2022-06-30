<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleCounting extends Model
{   
    // protected $table = 'vehicle_countings';

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function residentialGate()
    {
        return $this->belongsTo(ResidentialGate::class);
    }
}






