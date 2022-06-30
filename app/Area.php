<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function clusters()
    {
        return $this->hasMany(Cluster::class);
    }
}
