<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
