<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CameraType extends Model
{
    public function cameras()
    {
        return $this->hasMany(Camera::class);
    }
}
