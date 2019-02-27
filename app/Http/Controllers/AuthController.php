<?php

namespace App\Http\Controllers;

use App\User;
use Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\RegisterFormRequest;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        if(empty($request->id))
        {
            $user = new User;
            $user->isAdmin=($request->isAdmin=="true")?true:false;
            if($user->isAdmin == true){
                $user->presentAddress = "NA";
                $user->permanentAddress = "NA";
            }
            if(empty($request->password)){
                return Response::json("Password field is required", 400);
            }
        }
        else
        {
            $user = User::find($request->id);
        }
        
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->isActive=($request->isActive=="true")?true:false;
        $user->mobileNumber = $request->mobileNumber;
        $user->dateOfJoin=new \Datetime($request->dateOfJoin);
        $user->dateOfBirth=new \Datetime($request->dateOfBirth);

        if(!$user->isAdmin){
            //process address round
            $user->fatherName = $request->fatherName;
            $user->motherName = $request->motherName;
            $user->designationId = $request->designationId;
            $user->dateOfBirth = $request->dateOfBirth ?? null;
            $user->presentAddress = $request->presentAddress ?? "NA";
            $user->permanentAddress = $request->permanentAddress ?? "NA";
            $user->drivingLicence = $request->drivingLicence;
            $user->panNumber = $request->panNumber;
            $user->adharNumber = $request->adharNumber;
            $user->passportNumber = $request->passportNumber;
            $user->currentSalary = $request->currentSalary ?? "0";
            $user->maritalStatus = $request->maritalStatus ?? null;
        }
        
        $image = $request->file('profilePic');
        if($image instanceof UploadedFile){
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile');
            $image->move($destinationPath, $imageName);
            $user->profilePic = '/uploads/profile/'.$imageName;
        }

        if(!empty($request->password))
            $user->password = bcrypt($request->password);
        
        try {
            $user->save();
            return response([
                'status' => 'success',
                'data' => $user
            ], 200);
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for email", 500);
            else
                return Response::json("Server error : ".$e->getMessage(), 500);
        }
    }

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'msg' => 'Invalid Credentials.'
                ], 400);
        }

        $user = User::find(\Auth::user()->id);
        if(!$user->isActive){
            JWTAuth::invalidate($token);
            return response()->json(['access_denied'], 500);
        }
        $user = \Auth::user();
        $customClaims=['userId'=>$user->id, 'fullName'=>$user->firstName." ".$user->lastName, 'email'=>$user->email, 'isAdmin'=> $user->isAdmin];
        $token = JWTAuth::fromUser(\Auth::user(), $customClaims);
        return response([
                'status' => 'success',
                'token' => $token
            ]);
    }

    public function user(Request $request)
    {
        $user = User::find(\Auth::user()->id);
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
                'status' => 'success',
                'msg' => 'Logged out Successfully.'
            ], 200);
    }

    public function refresh()
    {
        return response([
         'status' => 'success'
        ]);
    }
}
