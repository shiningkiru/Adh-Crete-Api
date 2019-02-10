<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'regionName'
    ];
    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(State::class, 'stateId');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'regionId');
    }
}
