<?php

namespace App\Http\Controllers;

use Response;
use Validator;
use App\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function listDesignation(Request $request){
        return Designation::all();
    }

    public function getDesignation(Request $request, $id){
        return Designation::find($id);
    }

    public function updateDesignation(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:designations,id',
            'designationTitle' => 'required|unique:designations,designationTitle,'.$request->id.',id|max:190',
            'targetArea' => 'required|in:admin,general,country,state,region,city,block'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $designation = new Designation();
            $designation->targetArea = $request->targetArea;
        }
        else
        {
            $designation = Designation::find($request->id);
        }
        $designation->designationTitle = $request->designationTitle;
        try {
            $designation->save();
            return $designation;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for title", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteDesignation(Request $request,$id) {
        try {
            $designation = Designation::findOrFail($id);
            $designation->delete();
            return $designation;
        }catch(\Exception $e) {
            return Response::json("Designation not found", 500);
        }
    }
}
