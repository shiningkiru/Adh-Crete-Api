<?php

namespace App\Http\Controllers;

use Response;
use App\State;
use Validator;
use Illuminate\Http\Request;

class StateController extends Controller
{
    
    public function listStates(Request $request){
        return State::with('country')->get();
    }

    public function getState(Request $request, $id){
        return State::with('country')->find($id);
    }

    public function updateState(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:states,id',
            'stateName' => 'required|unique:states,stateName,'.$request->id.',id|max:190',
            'countryId' => 'required|exists:countries,id'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $state = new State();
        }
        else
        {
            $state = State::find($request->id);
        }
        $state->stateName = $request->stateName;
        $state->countryId = $request->countryId;
        try {
            $state->save();
            return $state;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for name", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteState(Request $request,$id) {
        try {
            $state = State::findOrFail($id);
            $state->delete();
            return $state;
        }catch(\Exception $e) {
            return Response::json("State not found", 500);
        }
    }
}
