<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'countryName', 'countryCode'
    ];
    public $timestamps = false;

    public function states()
    {
        return $this->hasMany(State::class, 'countryId');
    }
}
