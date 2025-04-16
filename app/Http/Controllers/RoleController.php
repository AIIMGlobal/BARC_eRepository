<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if(Gate::allows('all_roles', $user)){

            if($user->role_id == 1){
                $roles = Role::with('createdUser')->get();
            }else{
                $roles = Role::with('createdUser')->where('id', '!=', 1)->get();
            }

            return view('backend.admin.role.index', compact('roles'));

        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('add_role', $user)){
            $role = new Role;
            $role->name_en = $request->roleName;
            $role->name_bn = $request->roleName;
            $role->display_name = $request->roleName;
            $role->sl = $request->sl;
            $role->status = 1;
            $role->created_by = Auth::user()->id;
            $role->save();

            return redirect()->route('admin.role.index')->with('success', 'Role added successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function active($id)
    {
        $user = Auth::user();
        if(Gate::allows('delete_role', $user)){
            $role = Role::where('id', $id)->first();
            $role->status = 1;
            $role->save();
            return redirect()->route('admin.role.index')->with('success', 'Role activated successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function disable($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_role', $user)) {
            $role = Role::where('id', $id)->first();

            $role->status = 2;

            $role->save();

            return redirect()->route('admin.role.index')->with('success', 'Role disabled successfully..!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_role', $user)) {
            $role = Role::where('id', $id)->first();

            $role->name_en      = $request->roleName;
            $role->name_bn      = $request->roleName;
            $role->display_name = $request->roleName;
            $role->sl           = $request->sl;
            $role->updated_by   = Auth::user()->id;

            $role->save();

            return redirect()->route('admin.role.index')->with('success', 'Role updated successfully..!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}
