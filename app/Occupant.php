<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function gates()
    {
        return $this->hasMany(Gate::class);
    }
}
