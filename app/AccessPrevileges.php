<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessPrevileges extends Model
{
    protected $fillable = [
        'permission', 'moduleName'
    ];

    public $timestamps = false;

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designationId');
    }
}
