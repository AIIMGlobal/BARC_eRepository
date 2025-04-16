<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use Auth;
use App\Models\Designation;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('all_designations', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $designations = Designation::with('createdUser')->where('name', 'like', '%'.$request->name.'%')->paginate(15);
            }else{
                $designations = Designation::with('createdUser')->latest()->paginate(15);
            }
            return view('backend.admin.designation.index', compact('designations'));
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('add_designation', $user)){
            $designation = new Designation;
            $designation->name = $request->name;
            $designation->created_by = Auth::user()->id;
            $designation->status = 1;
            $designation->save();
            return redirect()->route('admin.designation.index')->with('success', 'New Designation has been added');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_designation', $user)){
            $designation = Designation::where('id', $id)->first();
            $designation->name = $request->name;
            $designation->created_by = Auth::user()->id;
            $designation->status = 1;
            $designation->save();
            return redirect()->route('admin.designation.index')->with('success', 'Designation has been updated');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }
}
