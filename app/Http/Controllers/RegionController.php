<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;
use Validator;
use Response;

class RegionController extends Controller
{
    
    public function listRegions(Request $request){
        return Region::with('state')->get();
    }

    public function getRegion(Request $request, $id){
        return Region::with('state')->find($id);
    }

    public function updateRegion(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:regions,id',
            'regionName' => 'required|unique:regions,regionName,'.$request->id.',id|max:190',
            'stateId' => 'required|exists:states,id'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $region = new Region();
        }
        else
        {
            $region = Region::find($request->id);
        }
        $region->regionName = $request->regionName;
        $region->stateId = $request->stateId;
        try {
            $region->save();
            return $region;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for name", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteRegion(Request $request,$id) {
        try {
            $region = Region::findOrFail($id);
            $region->delete();
            return $region;
        }catch(\Exception $e) {dd($e);
            return Response::json("Region not found", 500);
        }
    }
}
