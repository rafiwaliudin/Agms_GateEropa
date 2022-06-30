<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(
                Cluster::class,
                'vehicle_clusters',
                'vehicle_id',
                'cluster_id');
    }
}
