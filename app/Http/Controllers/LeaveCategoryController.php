<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

/* included models */
use App\Models\LeaveCategory;
use App\Models\User;

class LeaveCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_leave_category', $user)) {
            $query = LeaveCategory::latest();

            if (isset($request->name_en) && $request->name_en != '') {
                $query->where('name_en', 'like', '%'.$request->name_en.'%');
            }
            
            $categorys = $query->where('status', '!=', 2)->paginate(20);

            return view('backend.admin.leaveCategory.index', compact('categorys'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('create_leave_category', $user)) {
            $menu_expand = route('admin.leaveCategory.index');

            return view('backend.admin.leaveCategory.create', compact('menu_expand'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (Gate::allows('create_leave_category', $user)) {
            $this->validate($request, [
                'name_en'  => 'required',
            ]);

            $category =  new LeaveCategory;

            $category->name_en      = $request->name_en;
            $category->day_number   = $request->day_number ?? 0;
            $category->status       = $request->status ?? 0;
            $category->created_by   = Auth::id();

            $category->save();

            return redirect()->back()->with('success', "New leave category added successfully!");
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        if (Gate::allows('show_leave_category', $user)) {
            $menu_expand = route('admin.leaveCategory.index');

            $id = Crypt::decryptString($id);
            $category = LeaveCategory::where('id', $id)->first();

            return view('backend.admin.leaveCategory.show', compact('menu_expand', 'category'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_leave_category', $user)) {
            $menu_expand = route('admin.leaveCategory.index');

            $id = Crypt::decryptString($id);
            $category = LeaveCategory::where('id', $id)->first();

            return view('backend.admin.leaveCategory.edit', compact('menu_expand', 'category'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (Gate::allows('edit_leave_category', $user)) {
            $this->validate($request, [
                'name_en'  => 'required',
            ]);

            $id = Crypt::decryptString($id);
            $category = LeaveCategory::where('id', $id)->first();

            $category->name_en      = $request->name_en;
            $category->day_number   = $request->day_number ?? 0;
            $category->status       = $request->status ?? 0;
            $category->updated_by   = Auth::id();

            $category->save();

            return redirect()->back()->with('success', "Leave category updated successfully!");
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_leave_category', $user)) {
            $id = Crypt::decryptString($id);
            
            $category = LeaveCategory::where('id', $id)->first();

            $category->status = 2;

            $category->save();

            return redirect()->route('admin.leaveCategory.index')->with('success', 'Leave category deleted successfully!');
        } else {
            return abort(403, "You don't have permission!");
        }
    }
}
