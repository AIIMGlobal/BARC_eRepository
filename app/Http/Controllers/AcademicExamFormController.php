<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\AcademicExamForm;
use App\Models\Institute;
use App\Models\ExamCategory;
use App\Models\Board;
use App\Models\SubjectCategory;

class AcademicExamFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_educational_form', $user)){
            
            $exam_forms_query = AcademicExamForm::whereIn('status', [0,1]);

            if ($request->name and $request->name != '') {
                $exam_forms_query->where('name_en', 'like', '%'.$request->name.'%');
            }
            
            $exam_forms  = $exam_forms_query->orderBy('sl','ASC')->paginate(20);
            
            
            return view('backend.admin.educational_form.index', compact('exam_forms'));

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
        if(Gate::allows('add_educational_form', $user)){
            $menu_expand = route('admin.education_form.index');
            $institutes = Institute::where('status', 1)->get();
            $exam_categories = ExamCategory::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $subject_categories = SubjectCategory::where('status', 1)->get();
            return view('backend.admin.educational_form.create', compact('menu_expand','institutes','exam_categories','boards','subject_categories'));
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
        // dd($request->all());
        $user = Auth::user();
        if(Gate::allows('add_educational_form', $user)){
            
            $this->validate($request, [
                "name_en" => 'required|unique:academic_exam_forms',
            ]);



            $exam = new AcademicExamForm;
            $exam->sl = $request->sl;
            $exam->name_en = $request->name_en;
            $exam->roll = $request->roll;
            $exam->pass_year = $request->pass_year;
            $exam->institute_ids = $request->institute_ids ? implode(',', $request->institute_ids):NULL;
            $exam->institute_name = $request->institute_name;
            $exam->exam_category_id = $request->exam_category_id;
            $exam->exam_name = $request->exam_name;
            $exam->board_ids = $request->board_ids ? implode(',',$request->board_ids):NULL;
            $exam->board_name = $request->board_name;
            $exam->reg_no = $request->reg_no;
            $exam->subject_category_id = $request->subject_category_id;
            $exam->subject_name = $request->subject_name;
            $exam->result_type = $request->result_type;
            $exam->duration_id = $request->duration_id;
            $exam->status = $request->status ?? 0;
            $exam->created_by = $user->id;
            $exam->certificate_file = $request->certificate_file ?? 0;
            $exam->save();

            return redirect()->route('admin.education_form.index')->with('success', 'Exam form updated successfully..!');

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
        if(Gate::allows('edit_educational_form', $user)){
            $exam = AcademicExamForm::where('id', $id)->first();
            $exam->sl = $request->sl;
            $exam->name_en = $request->name_en;
            $exam->roll = $request->roll;
            $exam->pass_year = $request->pass_year;
            $exam->institute_ids = $request->institute_ids ? implode(',', $request->institute_ids):NULL;
            $exam->institute_name = $request->institute_name;
            $exam->exam_category_id = $request->exam_category_id;
            $exam->exam_name = $request->exam_name;
            $exam->board_ids = $request->board_ids ? implode(',',$request->board_ids):NULL;
            $exam->board_name = $request->board_name;
            $exam->reg_no = $request->reg_no;
            $exam->subject_category_id = $request->subject_category_id;
            $exam->subject_name = $request->subject_name;
            $exam->result_type = $request->result_type;
            $exam->duration_id = $request->duration_id;
            $exam->status = $request->status ?? 0;
            $exam->updated_by = $user->id;
            $exam->certificate_file = $request->certificate_file ?? 0;
            $exam->save();

            return redirect()->route('admin.education_form.index')->with('success', 'Educational form updated successfully..!');

        }else{

            return abort(403, "You don't have permission..!");

        }
    }

    public function show($id)
    {
        $user = Auth::user();
        if(Gate::allows('view_educational_form', $user)){
            $menu_expand = route('admin.education_form.index');
            $educational_form = AcademicExamForm::where('id', $id)->first();
            $institutes = Institute::where('status', 1)->get();
            $exam_categories = ExamCategory::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $subject_categories = SubjectCategory::where('status', 1)->get();
            return view('backend.admin.educational_form.view', compact('menu_expand', 'educational_form','institutes','exam_categories','boards','subject_categories'));
        }else{
            return abort(403, "You don't have permission.");
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_educational_form', $user)){
            $menu_expand = route('admin.education_form.index');
            $educational_form = AcademicExamForm::where('id', $id)->first();
            $institutes = Institute::where('status', 1)->get();
            $exam_categories = ExamCategory::where('status', 1)->get();
            $boards = Board::where('status', 1)->get();
            $subject_categories = SubjectCategory::where('status', 1)->get();
            
            return view('backend.admin.educational_form.edit', compact('menu_expand', 'educational_form', 'institutes', 'exam_categories','boards','subject_categories'));
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
        if(Gate::allows('delete_educational_form', $user)){
            $exam = AcademicExamForm::where('id', $id)->first();
            $exam->status = 2;
            $exam->save();

            return redirect()->route('admin.education_form.index')->with('success', 'Educational form deleted successfully..!');

        }else{

            return abort(403, "You don't have permission..!");

        }
    }
}
