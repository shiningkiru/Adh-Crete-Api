<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'cityName'
    ];
    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class, 'regionId');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class, 'cityId');
    }

    public function userArea()
    {
        return $this->hasMany(UserArea::class, 'cityId');
    }
}
