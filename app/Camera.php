<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    public function cameraType()
    {
        return $this->belongsTo(CameraType::class);
    }
}
