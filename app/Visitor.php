<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }

    public function gates()
    {
        return $this->hasMany(Gate::class);
    }

    public function people_countings()
    {
        return $this->hasMany(PeopleCounting::class);
    }

    public function vehicle_countings()
    {
        return $this->hasMany(VehicleCounting::class);
    }

    public function intruder_countings()
    {
        return $this->hasMany(IntruderCounting::class);
    }

    public function history_gates()
    {
        return $this->hasMany(HistoryGate::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(
                Cluster::class,
                'visitor_clusters',
                'visitor_id',
                'cluster_id');
    }

}
