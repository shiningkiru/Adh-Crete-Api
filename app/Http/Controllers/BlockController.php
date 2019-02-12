<?php

namespace App\Http\Controllers;

use Response;
use App\Block;
use Validator;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    
    public function listBlocks(Request $request){
        return Block::with('city')->get();
    }

    public function getBlock(Request $request, $id){
        return Block::with('city')->find($id);
    }

    public function updateBlock(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:blocks,id',
            'blockName' => 'required|unique:blocks,blockName,'.$request->id.',id|max:190',
            'cityId' => 'required|exists:cities,id'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }

        if(empty($request->id))
        {
            $block = new Block();
        }
        else
        {
            $block = Block::find($request->id);
        }
        $block->blockName = $request->blockName;
        $block->cityId = $request->cityId;
        try {
            $block->save();
            return $block;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for name", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function deleteBlock(Request $request,$id) {
        try {
            $block = Block::findOrFail($id);
            $block->delete();
            return $block;
        }catch(\Exception $e) {
            return Response::json("Block not found", 500);
        }
    }
}
