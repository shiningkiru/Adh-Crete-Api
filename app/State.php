<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'stateName'
    ];
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class, 'countryId');
    }

    public function regions()
    {
        return $this->hasMany(Region::class, 'stateId');
    }
}
