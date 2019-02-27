<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAdmin(Request $request) {
        return User::with('designation')->where('isAdmin', true)->get();
    }

    public function getStaff(Request $request) {
        return User::with('designation')->where('isAdmin', false)->get();
    }

    public function getUserData(Request $request, $id) {
        return User::with('designation')->find($id);
    }

    public function updateUserArea(Request $request){
        
    }
}
