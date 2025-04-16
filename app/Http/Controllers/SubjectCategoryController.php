<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Subject;
use App\Models\SubjectCategory;

class SubjectCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_subject_category', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $subjectCategories = SubjectCategory::where('name_en', 'like', '%'.$request->name.'%')->where(function($query) {
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(20);
            }else{
                $subjectCategories = SubjectCategory::where('status', 0)->orWhere('status', 1)->latest()->paginate(20);
            }
            $subjects = Subject::where('status', 1)->get();
            return view('backend.admin.academicForm.subjectCategory.index', compact('subjectCategories', 'subjects'));

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
        if(Gate::allows('add_subject_category', $user)){
            $menu_expand = route('admin.subjectCategory.index');
            $subjects = Subject::where('status', 1)->get();
            return view('backend.admin.academicForm.subjectCategory.create', compact('menu_expand', 'subjects'));
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
        if(Gate::allows('add_subject_category', $user)){
            $this->validate($request, [
                'name_en' => 'required',
                'subject_ids' => 'required',
            ]);
            
            $subjectCategory = new SubjectCategory;
            $subjectCategory->name_en = $request->name_en;
            $subjectCategory->subject_ids = implode(',', $request->subject_ids);
            $subjectCategory->status = $request->status ?? 0;
            $subjectCategory->created_by = $user->id;
            $subjectCategory->save();
            return redirect()->route('admin.subjectCategory.index')->with('success', 'New subject category added successfully..!');

        }else{
            return abort(403, "You don't have permission");
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
        if(Gate::allows('edit_subject_category', $user)){
            $this->validate($request, [
                'name_en' => 'required',
                'subject_ids' => 'required',
            ]);

            $subjectCategory = SubjectCategory::where('id', $id)->first();
            $subjectCategory->name_en = $request->name_en;
            $subjectCategory->subject_ids = implode(',', $request->subject_ids);
            $subjectCategory->status = $request->status ?? 0;
            $subjectCategory->updated_by = $user->id;
            $subjectCategory->save();
            return redirect()->route('admin.subjectCategory.index')->with('success', 'Subject category updated successfully..!');
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
        if(Gate::allows('delete_subject_category', $user)){
            $subjectCategory = SubjectCategory::where('id', $id)->first();
            $subjectCategory->status = 2;
            $subjectCategory->save();
            return redirect()->route('admin.subjectCategory.index')->with('success', 'Subject category deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }
}
