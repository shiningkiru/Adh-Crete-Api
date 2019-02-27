<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArea extends Model
{
    protected $fillable = [
        'isDefault'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisorId');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'countryId');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'stateId');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'regionId');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId');
    }

    public function block()
    {
        return $this->belongsTo(Block::class, 'blockId');
    }

}
