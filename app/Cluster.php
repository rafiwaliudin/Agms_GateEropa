<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function residentialGates()
    {
        return $this->hasMany(ResidentialGate::class);
    }

    public function vehicles()
    {
        return $this->belongsToMany(
                Vehicle::class,
                'vehicle_clusters',
                'cluster_id',
                'vehicle_id');
    }

    public function visitors()
    {
        return $this->belongsToMany(
                Visitor::class,
                'visitor_clusters',
                'cluster_id',
                'visitor_id');
    }
}
