<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

use Auth;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if(Gate::allows('assign_permission_list', $user)){
            $selected_role_id = Session::has('selected_role_id') ? Session::get('selected_role_id') : Auth::user()->role_id;

            if ($user->role_id == 1) {
                $roles = Role::where('status', 1)->get();
            } else {
                $roles = Role::where('id', '!=', 1)->where('status', 1)->get();
            }
            
            $rolePermissions = RolePermission::with('permissionName')->where('role_id', $selected_role_id)->get();

            $assignedIds = array();

            foreach($rolePermissions as $rp){
                array_push($assignedIds, $rp->permission_id);
            }

            $unassignedPermissions = Permission::whereNotIn('id', $assignedIds)->get();
            
            return view('backend.admin.rolePermission.index', compact('roles', 'rolePermissions', 'unassignedPermissions', 'selected_role_id'));
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function showPermission($roleId)
    {
        $user = Auth::user();
        if(Gate::allows('assign_permission_list', $user)){
            // only super admin will see all roles
            if($user->role_id == 1){
                $roles = Role::where('status', 1)->get();
            }else{
                $roles = Role::where('id', '!=', 1)->where('status', 1)->get();
            }
            $rolePermissions = RolePermission::with('permissionName')->where('role_id', $roleId)->get();
            $assignedIds = array();
            foreach($rolePermissions as $rp){
                array_push($assignedIds, $rp->permission_id);
            }
            $unassignedPermissions = Permission::whereNotIn('id', $assignedIds)->get();
            return response()->json([
                'roles' => $roles,
                'rolePermissions' => $rolePermissions,
                'unassignedPermissions' => $unassignedPermissions,
            ]);
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function removePermission(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('remove_assign_permission', $user)) {
            if (!$request->removePermission) {
                return redirect()->route('admin.rolePermission.index')->with('error', 'Select permissions first..!');
            } else {
                $permissionsToRemove = RolePermission::where('role_id', $request->hiddenRoleId)->whereIn('permission_id', $request->removePermission)->delete();

                return redirect()->route('admin.rolePermission.index')->with('selected_role_id', $request->hiddenRoleId)->with('success', 'Permission removed successfully..!');
            }
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function givePermission(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('assign_permission', $user)) {
            if (!$request->givePermission) {
                return redirect()->route('admin.rolePermission.index')->with('error', 'Select permissions first..!');
            } else {
                $addedCount = 0;
                foreach($request->givePermission as $permission) {
                    $permissionsToGive = RolePermission::firstOrCreate(
                        [
                            'permission_id' => $permission,
                            'role_id' => $request->hiddenRoleId,
                        ],
                        [
                            'created_by' => Auth::user()->id,
                        ]
                    );
                    if ($permissionsToGive->wasRecentlyCreated) {
                        $addedCount++;
                    }
                }

                $message = $addedCount > 0 
                    ? "Permission given successfully..! ({$addedCount} new)" 
                    : 'No new permissions were added (already assigned).';

                return redirect()->route('admin.rolePermission.index')
                    ->with('selected_role_id', $request->hiddenRoleId)
                    ->with('success', $message);
            }
        } else {
            return abort(403, "You don't have permission..!");
        }

    }
}