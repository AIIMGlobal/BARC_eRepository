<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_departments', $user)) {
            $query = Department::query();

            if (($request->name != '') && ($request->name != NULL)) {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            $departments = $query->latest()->paginate(20);

            return view('backend.admin.department.index', compact('departments'));

        } else {
            return abort(403, "You don't have permission..!");
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_host', $user)){

            $validated = $request->validate([
                'name' => 'required',
            ]);

            $department = new Department;
            $department->name = $request->name;
            $department->status = $request->status ?? 0;
            $department->created_by = $user->id;
            $department->save();
            
            return redirect()->route('admin.department.index')->with('success', 'New Department added successfully..!');

        }else{
            return abort(403, "You don't have permission..!");
        }   
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_department')) {
            $department = Department::where('id', $id)->first();
            
            $department->name = $request->name;
            $department->status = $request->status ?? 0;
            $department->save();

            return redirect()->route('admin.department.index')->with('success', 'Department Updated successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if(Gate::allows('delete_department')) {
            $department = Department::where('id', $id)->first();
            
            $department->delete();

            return redirect()->route('admin.department.index')->with('success', 'Department Deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
