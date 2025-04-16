<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_subject', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $subjects = Subject::where('name_en', 'like', '%'.$request->name.'%')->where(function($query){
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(20);
            }else{
                $subjects = Subject::where('status', 0)->orWhere('status', 1)->latest()->paginate(20);
            }

            return view('backend.admin.academicForm.subject.index', compact('subjects'));

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
        if(Gate::allows('add_subject', $user)){
            $menu_expand = route('admin.subject.index');
            return view('backend.admin.academicForm.subject.create', compact('menu_expand'));
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
        if(Gate::allows('add_subject', $user)){

            $this->validate($request, [
                "name_en" => 'required|unique:subjects',
            ]);

            $subject = new Subject;
            $subject->name_en = $request->name_en;
            $subject->status = $request->status ?? 0;
            $subject->created_by = $user->id;
            $subject->save();

            return redirect()->route('admin.subject.index')->with('success', 'New subject added successfully..!');

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
        if(Gate::allows('edit_subject', $user)){
            $this->validate($request, [
                "name_en" => 'required|unique:subjects,name_en,' . $id,
            ]);
            $subject = Subject::where('id', $id)->first();
            $subject->name_en = $request->name_en;
            $subject->status = $request->status ?? 0;
            $subject->updated_by = $user->id;
            $subject->save();

            return redirect()->route('admin.subject.index')->with('success', 'Subject updated successfully..!');
            
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
        if(Gate::allows('delete_subject', $user)){
            $subject = Subject::where('id', $id)->first();
            $subject->status = 2;
            $subject->save();
            return redirect()->route('admin.subject.index')->with('success', 'Subject deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
