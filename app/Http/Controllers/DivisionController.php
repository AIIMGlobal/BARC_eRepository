<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use Auth;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_region', $user)){
            $query = Division::orderBy('sl','ASC');
            $regions = $query->paginate(20);
            return view('backend.admin.region.index', compact("regions"));
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('add_region', $user)){
            $validated = $request->validate([
                'name' => 'required|unique:divisions',
            ]);
            $data = $request->all();
            $data['created_by'] = auth()->user()->id;
            Division::create($data);
            return back()->with('success', 'New region added successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function view($id)
    {
        $user = Auth::user();
        if(Gate::allows('view_region', $user)){
            $region = Division::findOrFail($id);
            $menu_expand = route('admin.region.index');
            return view('backend.admin.region.view', compact("region","menu_expand"));
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function update($id,Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('edit_region', $user)){
            $request->validate([
                'name' => 'required|unique:divisions,name,' . $id,
            ]);

            $data = $request->all();
            $data['status'] = $request->status ?? 0;
            $data['updated_by'] = auth()->user()->id;
            Division::find($id)->update($data);

            return back()->with('success', 'Division updated successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }

    public function destroy($id)
    {
        $user = Auth::user();
        if(Gate::allows('delete_region', $user)){
            Division::find($id)->delete();
            return back()->with('success', 'Division deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }

    }
}
