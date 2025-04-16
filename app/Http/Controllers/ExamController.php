<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Exam;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_exam', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $exams = Exam::where('name_en', 'like', '%'.$request->name.'%')->where(function($query){
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(20);
            }else{
                $exams = Exam::where('status', 0)->orWhere('status', 1)->latest()->paginate(20);
            }
            
            return view('backend.admin.academicForm.exam.index', compact('exams'));

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
        if(Gate::allows('add_exam', $user)){
            $menu_expand = route('admin.exam.index');
            return view('backend.admin.academicForm.exam.create', compact('menu_expand'));
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
        if(Gate::allows('add_exam', $user)){
            
            $this->validate($request, [
                "name_en" => 'required|unique:exams',
            ]);

            $exam = new Exam;
            $exam->sl = $request->sl;
            $exam->name_en = $request->name_en;
            $exam->status = $request->status ?? 0;
            $exam->created_by = $user->id;
            $exam->save();

            return back()->with('success', 'New Exam added successfully..!');

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
        $this->validate($request, [
            "name_en" => 'required|unique:exams,name_en,' . $id,
        ]);
        $user = Auth::user();
        if(Gate::allows('edit_exam', $user)){
            $exam = Exam::where('id', $id)->first();
            $exam->sl = $request->sl;
            $exam->name_en = $request->name_en;
            $exam->status = $request->status ?? 0;
            $exam->updated_by = $user->id;
            $exam->save();

            return redirect()->route('admin.exam.index')->with('success', 'Exam updated successfully..!');

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
        if(Gate::allows('delete_exam', $user)){
            $exam = Exam::where('id', $id)->first();
            $exam->status = 2;
            $exam->save();

            return redirect()->route('admin.exam.index')->with('success', 'Exam deleted successfully..!');

        }else{

            return abort(403, "You don't have permission..!");

        }
    }
}
