<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'designationTitle', 'targetArea'
    ];

    public $timestamps = false;

    public function previleges()
    {
        return $this->hasMany(AccessPrevileges::class, 'designationId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'designationId');
    }
}
