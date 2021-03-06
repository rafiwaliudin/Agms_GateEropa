<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
