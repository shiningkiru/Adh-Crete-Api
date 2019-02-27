<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'blockName'
    ];
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId');
    }

    public function userArea()
    {
        return $this->hasMany(UserArea::class, 'blockId');
    }
}
