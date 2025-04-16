<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

use App\Models\Duration;

class DurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_duration', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $durations = Duration::where('name_en', 'like', '%'.$request->name.'%')->where('status', '!=', 2)->latest()->paginate(15);
            }else{
                $durations = Duration::where('status', '!=', 2)->latest()->paginate(15);
            }
            return view('backend.admin.duration.index', compact('durations'));
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
        if(Gate::allows('add_duration', $user)){
            $menu_expand = route('admin.duration.index');
            return view('backend.admin.duration.create', compact('menu_expand'));
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
        if(Gate::allows('add_duration', $user)){
            $this->validate($request, [
                'name_en' => 'required',
            ]);

            $duration = new Duration;
            $duration->name_en = $request->name_en;
            $duration->status = $request->status ?? 0;
            $duration->created_by = $user->id;
            $duration->save();

            return redirect()->route('admin.duration.index')->with('success', 'New duration added successfully..!');

        }else{
            return abort(403, "You don't have permission..!");
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
        $user = Auth::user();
        if(Gate::allows('view_duration', $user)){
            $id = Crypt::decryptString($id);
            $menu_expand = route('admin.duration.index');
            $duration = Duration::where('id', $id)->first();
            return view('backend.admin.duration.show', compact('menu_expand', 'duration'));
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
        if(Gate::allows('edit_duration', $user)){
            $this->validate($request, [
                'name_en' => 'required',
            ]);

            $duration = Duration::where('id', $id)->first();
            $duration->name_en = $request->name_en;
            $duration->status = $request->status ?? 0;
            $duration->updated_by = $user->id;
            $duration->save();

            return redirect()->route('admin.duration.index')->with('success', 'Duration updated successfully..!');

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
        if(Gate::allows('delete_duration', $user)){
            $id = Crypt::decryptString($id);
            $duration = Duration::where('id', $id)->first();
            $duration->status = 2;
            $duration->save();
            return redirect()->route('admin.duration.index')->with('success', 'Duration deleted successfully..!');
        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
