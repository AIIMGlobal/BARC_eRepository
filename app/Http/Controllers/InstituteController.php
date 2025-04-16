<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Institute;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_institute', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $institutes = Institute::where('name_en', 'like', '%'.$request->name.'%')->where(function($query){
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(15);
            }else{
                $institutes = Institute::where('status', 0)->orWhere('status', 1)->latest()->paginate(15);
            }
            return view('backend.admin.academicForm.institute.index', compact('institutes'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if(Gate::allows('add_institute', $user)){
            $menu_expand = route('admin.institute.index');
            return view('backend.admin.academicForm.institute.create', compact('menu_expand'));
        }else{
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
        if(Gate::allows('add_institute', $user)){
            
            $this->validate($request, [
                "name_en" => 'required|unique:institutes',
            ]);
            $institute = new Institute;
            $institute->name_en = $request->name_en;
            $institute->status = $request->status ?? 0;
            $institute->created_by = $user->id;
            $institute->save();
            return redirect()->route('admin.institute.index')->with('success', 'New institute added successfully..!');

        }else{
            return abort(403,"You don't have permission..!");
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
        if(Gate::allows('edit_institute', $user)){
            $this->validate($request, [
                "name_en" => 'required|unique:institutes,name_en,' . $id,
            ]);
            $institute = Institute::where('id', $id)->first();
            $institute->name_en = $request->name_en;
            $institute->status = $request->status ?? 0;
            $institute->updated_by = $user->id;
            $institute->save();
            return redirect()->route('admin.institute.index')->with('success', 'Institute updated successfully..!');

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
        if(Gate::allows('delete_institute', $user)){
            $institute = Institute::where('id', $id)->first();
            $institute->status = 2;
            $institute->save();
            return redirect()->route('admin.institute.index')->with('success', 'Institute deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
