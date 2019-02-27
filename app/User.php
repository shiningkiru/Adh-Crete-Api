<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'profilePic', 'email', 'mobileNumber', 'password', 'fatherName', 'motherName', 'dateOfBirth', 'dateOfJoin', 'presentAddress', 'permanentAddress', 'drivingLicence', 'panNumber', 'adharNumber', 'passportNumber', 'maritalStatus', 'currentSalary', 'isActive'
    ];

    

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designationId');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function myArea()
    {
        return $this->hasMany(UserArea::class, 'userId');
    }

    public function supervisedArea()
    {
        return $this->hasMany(UserArea::class, 'supervisorId');
    }
}
