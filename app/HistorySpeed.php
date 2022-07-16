<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorySpeed extends Model
{
    protected $table = 'history_speed';
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function residentialGate()
    {
        return $this->belongsTo(ResidentialGate::class);
    }
}
