<?php

namespace App\Http\Controllers;

use Validator;
use App\Country;
use Illuminate\Http\Request;
use Response;

class CountryController extends Controller
{
    public function listCountries(Request $request){
        return Country::all();
    }

    public function getCountry(Request $request, $id){
        return Country::find($id);
    }

    public function updateCountry(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:countries,id',
            'countryName' => 'required|unique:countries,countryName,'.$request->id.',id|max:190',
            'countryCode' => 'required|unique:countries,countryCode,'.$request->id.',id|max:190'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $country = new Country();
        }
        else
        {
            $country = Country::find($request->id);
        }
        $country->countryName = $request->countryName;
        $country->countryCode = $request->countryCode;
        try {
            $country->save();
            return $country;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for name or code", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteCountry(Request $request,$id) {
        try {
            $country = Country::findOrFail($id);
            $country->delete();
            return $country;
        }catch(\Exception $e) {
            return Response::json("Country not found", 500);
        }
    }
}
