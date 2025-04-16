<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\UserCategory;

class UserCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('user_category_management', $user)) {
            $query = UserCategory::query();

            if (($request->name != '') && ($request->name != NULL)) {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            $user_categories = $query->latest()->paginate(20);

            return view('backend.admin.user_category.index', compact('user_categories'));

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

            $user_category = new UserCategory;
            $user_category->name = $request->name;
            $user_category->status = $request->status ?? 0;
            $user_category->created_by = $user->id;
            $user_category->sl = $request->sl;
            $user_category->save();
            
            return redirect()->route('admin.user_category.index')->with('success', 'New User Category added successfully..!');

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
        if(Gate::allows('edit_user_category')) {
            $user_category = UserCategory::where('id', $id)->first();
            
            $user_category->name = $request->name;
            $user_category->status = $request->status ?? 0;
            $user_category->sl = $request->sl;
            $user_category->save();

            return redirect()->route('admin.user_category.index')->with('success', 'User Category Updated successfully..!');
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
        if(Gate::allows('delete_user_category')) {
            $user_category = UserCategory::where('id', $id)->first();
            
            $user_category->delete();

            return redirect()->route('admin.user_category.index')->with('success', 'User Category Deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
