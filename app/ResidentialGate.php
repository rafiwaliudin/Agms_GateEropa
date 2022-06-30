<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentialGate extends Model
{
    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function gate()
    {
        return $this->hasMany(Gate::class);
    }

    public function people_countings()
    {
        return $this->hasMany(PeopleCounting::class);
    }

    public function vehicle_countings()
    {
        return $this->hasMany(PeopleCounting::class);
    }

    public function intruder_countings()
    {
        return $this->hasMany(IntruderCounting::class);
    }

    public function history_gates()
    {
        return $this->hasMany(HistoryGate::class);
    }
}
