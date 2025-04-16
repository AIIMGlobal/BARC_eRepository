<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\ProjectCategory;
use Illuminate\Support\Facades\Crypt;

class ProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if(Gate::allows('all_project_category', $user)){
            $query = ProjectCategory::latest();

            if(isset($request->name) and $request->name != '') {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            $project_categories = $query->paginate(20);

            return view('backend.admin.project_category.index', compact('project_categories'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function create()
    {
        $user = Auth::user();

        if(Gate::allows('add_project_category', $user)){
            $menu_expand = route('admin.project_category.index');
            
            return view('backend.admin.project_category.create', compact('menu_expand'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('add_project_category', $user)){
            $category['name'] = $request->name;
            $category['created_by'] = $user->id;
            $category['status'] = $request->status ?? 0;
            ProjectCategory::create($category);
            return redirect()->route('admin.project_category.index')->with('success', 'Project Category added.');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function edit($id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_project_category', $user)){
            $id = Crypt::decryptString($id);
            $category = ProjectCategory::where('id',$id)->first();
            $menu_expand = route('admin.project_category.index');
            return view('backend.admin.project_category.edit', compact('menu_expand','category'));
        } else {
            return abort(403, "You don't have permission..!");
        }
        
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if(Gate::allows('edit_project_category', $user)){
            $category['name'] = $request->name;
            $category['updated_by'] = $user->id;
            $category['status'] = $request->status ?? 0;
            ProjectCategory::where('id',$id)->update($category);
            return redirect()->route('admin.project_category.index')->with('success', 'Project Category updated.');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function delete($id)
    {
        $id = Crypt::decryptString($id);
        ProjectCategory::where('id',$id)->delete();

        return redirect()->back()->with('success', 'Project Category deleted.');
    }

    public function show($id)
    {
        $user = Auth::user();
        if(Gate::allows('view_project_category', $user)){
            $id = Crypt::decryptString($id);
            $category = ProjectCategory::where('id',$id)->first();
            $menu_expand = route('admin.project_category.index');
            return view('backend.admin.project_category.view', compact('menu_expand','category'));
        } else {
            return abort(403, "You don't have permission..!");
        }
        
    }
}
