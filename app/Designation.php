<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'designationTitle', 'targetArea'
    ];

    public $timestamps = false;
}
