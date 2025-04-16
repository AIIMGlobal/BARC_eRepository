<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Exam;
use App\Models\ExamCategory;

class ExamCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_exam_category', $user)){
            if(($request->name != '') && ($request->name != '')){
                $examCategories = ExamCategory::where('name_en', 'like', '%'.$request->name.'%')->where(function($query){
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(20);
            }else{
                $examCategories = ExamCategory::where('status', 0)->orWhere('status', 1)->latest()->paginate(20);
            }
            $exams = Exam::where('status', 1)->get();
            return view('backend.admin.academicForm.examCategory.index', compact('examCategories', 'exams'));
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
        if(Gate::allows('add_exam_category', $user)){
            $menu_expand = route('admin.examCategory.index');
            $exams = Exam::where('status', 1)->get();

            return view('backend.admin.academicForm.examCategory.create', compact('menu_expand', 'exams'));
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
        if(Gate::allows('add_exam_category', $user)){

            $this->validate($request, [
                'name_en' => 'required',
                'exam_ids' => 'required'
            ]);

            $examCategory = new ExamCategory;
            $examCategory->name_en = $request->name_en;
            $examCategory->exam_ids = implode(",", $request->exam_ids);
            $examCategory->status = $request->status ?? 0;
            $examCategory->created_by = $user->id;
            $examCategory->save();

            return redirect()->route('admin.examCategory.index')->with('success', 'New exam category added successfully..!');

        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_exam_category', $user)){
            $menu_expand = route('admin.examCategory.index');
            $exams = Exam::where('status', 1)->get();
            $examCategory = ExamCategory::where('id', $id)->first();
            return view('backend.admin.academicForm.examCategory.edit', compact('menu_expand', 'exams', 'examCategory'));
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
        if(Gate::allows('edit_exam_category', $user)){
            
            $this->validate($request, [
                'name_en' => 'required',
                'exam_ids' => 'required'
            ]);

            $examCategory = ExamCategory::where('id', $id)->first();
            $examCategory->name_en = $request->name_en;
            $examCategory->exam_ids = implode(",", $request->exam_ids);
            $examCategory->status = $request->status ?? 0;
            $examCategory->updated_by = $user->id;
            $examCategory->save();
            return redirect()->route('admin.examCategory.index')->with('success', 'Exam category updated successfully..!');
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
        if(Gate::allows('delete_exam_category', $user)){
            $examCat = ExamCategory::where('id', $id)->first();
            $examCat->status = 2;
            $examCat->save();
            return redirect()->route('admin.examCategory.index')->with('success', 'Exam category deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
