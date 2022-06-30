<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class IntruderCounting extends Model
{
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function residentialGate()
    {
        return $this->belongsTo(ResidentialGate::class);
    }
    
}
