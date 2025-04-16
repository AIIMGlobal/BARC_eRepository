<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_permissions', $user)) {
            $query = Permission::with('createdUser');

            if (isset($request->name_en) && $request->name_en != '') {
                $query->where('name_en', 'like', '%'.$request->name_en.'%');
            }

            $permissions = $query->latest()->paginate(20);

            return view('backend.admin.permission.index', compact('permissions'));
        } else {
            return abort(403, "You don't have permission..!");
        }

    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if(Gate::allows('add_permission', $user)){
            $permission = new Permission;

            $permission->name_en = strtolower(str_replace(' ', '_', $request->name_en));
            $permission->name_bn = strtolower(str_replace(' ', '_', $request->name_en));
            $permission->status = 1;
            $permission->created_by = Auth::user()->id;

            $permission->save();
            return redirect()->route('admin.permission.index')->with('success', 'Permission created successfully..!');
        } else {
            return abort(403, "You don't have permission..!..!");
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_permission', $user)){
            $permission = Permission::where('id', $id)->first();
            $permission->name_en = strtolower(str_replace(' ', '_', $request->name_en));
            $permission->name_bn = strtolower(str_replace(' ', '_', $request->name_en));
            $permission->status = 1;
            $permission->created_by = Auth::user()->id;
            $permission->save();
            return redirect()->route('admin.permission.index')->with('success', 'Permission updated successfully..!');
        }else{
            return abort(403, "You don't have permission..!..!");
        }

    }

    public function delete($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_permission', $user)) {
            Permission::find($id)->delete();

            return back()->with('success', 'Permission deleted successfully..!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}
