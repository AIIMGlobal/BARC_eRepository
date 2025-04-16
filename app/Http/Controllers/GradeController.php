<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('manage_grade', $user)) {
            $query = Grade::whereIn('status', [0,1]);

            $query->where(function($query) use($request){
                if (isset($request->searchText) && ($request->searchText != '') && ($request->searchText != NULL)) {
                    $query->where('name_en', 'like', '%'.$request->searchText.'%');
                }
            });

            $grades = $query->latest()->paginate(20);
            
            return view('backend.admin.academicForm.grade.index', compact('grades'));
        } else {
            return abort(403, "You are not authorize to access this option!");
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

        $menu_expand = route('admin.grade.index');

        if (Gate::allows('add_grade', $user)) {
            return view('backend.admin.academicForm.grade.create', compact('menu_expand'));
        } else {
            return abort(403, "You are not authorize to access this option!");
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

        if (Gate::allows('add_grade', $user)) {
            
            $this->validate($request, [
                "name_en" => 'required',
            ]);

            $grade = new Grade;

            $grade->name_en     = $request->name_en;
            $grade->status      = $request->status ?? 0;
            $grade->created_by  = $user->id;

            $grade->save();

            return back()->with('success', 'Grade added successfully!');
        } else {
            return abort(403, "You are not authorize to access this option!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        if (Gate::allows('edit_grade', $user)) {
            $grade = Grade::where('id', $id)->first();

            $this->validate($request, [
                "name_en" => 'required',
            ]);

            $grade->name_en     = $request->name_en;
            $grade->status      = $request->status ?? 0;
            $grade->updated_by  = $user->id;

            $grade->save();

            return redirect()->route('admin.grade.index')->with('success', 'Grade updated successfully!');
        } else {
            return abort(403, "You are not authorize to access this option!");
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

        if (Gate::allows('delete_grade', $user)) {
            $grade = Grade::where('id', $id)->first();

            $grade->status = 2;

            $grade->save();

            return redirect()->route('admin.grade.index')->with('success', 'Grade deleted successfully!');
        } else {
            return abort(403, "You are not authorize to access this option!");
        }
    }
}
