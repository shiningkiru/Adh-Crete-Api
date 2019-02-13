<?php

namespace App\Http\Controllers;

use App\AccessPrevileges;
use Illuminate\Http\Request;
use Validator;
use Response;

class AccessPrevilegeController extends Controller
{
    private $modules = [
        'area-manager',
        'user-access'
    ];
    private function syncPermissions($designationId) {
        $permissionSet = AccessPrevileges::where('designationId', '=', $designationId)->get();
        $currentModules=[];
        foreach($permissionSet as $permission){
            if(!in_array($permission->moduleName, $this->modules)){
                $permission->delete();
            }else {
                $currentModules[] = $permission->moduleName;
            }
        }
        $newModules = array_diff($this->modules, $currentModules);
        forEach($newModules as $module){
            $accessPrevilege = new AccessPrevileges();
            $accessPrevilege->moduleName = $module;
            $accessPrevilege->designationId = $designationId;
            $accessPrevilege->permission = "allowed";
            $accessPrevilege->save();
        }
    }

    public function updatePermission(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:access_previleges,id',
            'permission' => 'required|in:allowed,denied,readonly'
        ]);

        if ($validator->fails()) 
        {
            return Response::json($validator->errors(), 400);
        }
        $accessPrevilege = AccessPrevileges::find($request->id);
        $accessPrevilege->permission = $request->permission;
        try {
            $accessPrevilege->save();
            return $accessPrevilege;
        }catch(\Exception $e) {
            if($e->getCode() == 23000)
                return Response::json("Duplicate entry for module", 500);
            else
                return Response::json("Server error ".$e->getCode(), 500);
        }
    }

    public function getPermissionByDesignation(Request $request, $id) {
        if (empty($id)) 
        {
            return Response::json(["Designation is required"], 400);
        }
        $this->syncPermissions($id);
        return AccessPrevileges::with('designation')->where('designationId', '=', $id)->get();
    }
}
