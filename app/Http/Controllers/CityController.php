<?php

namespace App\Http\Controllers;

use App\City;
use Response;
use Validator;
use Illuminate\Http\Request;

class CityController extends Controller
{
    
    public function listCities(Request $request){
        return City::with('region')->get();
    }

    public function getCity(Request $request, $id){
        return City::with('region')->find($id);
    }

    public function updateCity(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:cities,id',
            'cityName' => 'required|unique:cities,cityName,'.$request->id.',id|max:190',
            'regionId' => 'required|exists:regions,id'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $city = new City();
        }
        else
        {
            $city = City::find($request->id);
        }
        $city->cityName = $request->cityName;
        $city->regionId = $request->regionId;
        try {
            $city->save();
            return $city;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for name", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteCity(Request $request,$id) {
        try {
            $city = City::findOrFail($id);
            $city->delete();
            return $city;
        }catch(\Exception $e) {
            return Response::json("City not found", 500);
        }
    }
}
