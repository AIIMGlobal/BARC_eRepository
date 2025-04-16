<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/* included models */
use App\Models\Office;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('manage_office', $user)) {
            $orgs = Office::where('status', '!=', 2)->orderBy('name', 'asc')->get();
            $divisions = Division::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            $districts = District::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            $upazilas = Upazila::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();

            $query = Office::query();

            if (isset($request->office_id) && $request->office_id != '') {
                $query->where('id', $request->office_id);
            }

            $offices = $query->where('status', '!=', 2)->orderBy('name', 'asc')->get();
            
            if ($request->ajax()) {
                $html = view('backend.admin.office.table', compact('offices'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }

            return view('backend.admin.office.index', compact('offices', 'divisions', 'districts', 'upazilas', 'orgs'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function archived()
    {
        $offices = Office::where('status', 2)->latest()->paginate(15);
        $divisions = Division::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
        $districts = District::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
        $upazilas = Upazila::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();

        return view('backend.admin.office.archived', compact('offices', 'divisions', 'districts', 'upazilas'));
    }

    public function create()
    {
        $user = Auth::user();

        if(Gate::allows('add_office', $user)){
            $menu_expand = route('admin.office.index');

            $divisions = Division::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            $districts = District::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            $upazilas = Upazila::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            
            return view('backend.admin.office.create', compact('menu_expand', 'divisions', 'districts', 'upazilas'));

        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('add_office', $user)) {
            $validator = Validator::make($request->all(), [
                'name'      => 'required',
                'division'  => 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error!',
                    'errors'  => $validator->errors()
                ], 422);
            }
            
            try {
                if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $image = $file->storeAs('organizations', $fileName, 'public');
                } else {
                    $image = null;
                }

                $office = new Office;

                $office->name           = $request->name;
                $office->short_name     = $request->short_name;
                $office->division_id    = $request->division;
                $office->district_id    = $request->district;
                $office->upazila_id     = $request->upazila;
                $office->website_url    = $request->website_url;
                $office->logo           = $image;
                $office->created_by     = $user->id;
                $office->status         = $request->status ?? 0;

                $office->save();

                return response()->json([
                    'success' => true,
                    'message' => 'New Organization Added Successfully!',
                ]);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred. Please try again.',
                ], 500);
            }
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if(Gate::allows('view_office', $user)){
            $menu_expand = route('admin.office.index');

            $id = Crypt::decryptString($id);
            $office = Office::where('id', $id)->first();
            
            return view('backend.admin.office.view', compact('office', 'menu_expand'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function edit($id)
    {
        $user = Auth::user();

        if(Gate::allows('edit_office', $user)){
            $menu_expand = route('admin.office.index');

            $id = Crypt::decryptString($id);
            $office = Office::where('id', $id)->first();

            $divisions = Division::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            $districts = District::select('id', 'name_en')->where('division_id', ($office->division_id ?? 0))->where('status', 1)->orderBy('name_en', 'asc')->get();
            $upazilas = Upazila::select('id', 'name_en')->where('status', 1)->orderBy('name_en', 'asc')->get();
            
            return view('backend.admin.office.edit', compact('office', 'menu_expand', 'divisions', 'districts', 'upazilas'));
        }else{
            return abort(403, "You don't have permission..!");
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_office', $user)) {
            $validator = Validator::make($request->all(), [
                'name'      => 'required',
                'division'  => 'required',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error!',
                    'errors'  => $validator->errors()
                ], 422);
            }
            
            try {
                $id = Crypt::decryptString($id);
                $office = Office::where('id', $id)->first();

                if ($request->hasFile('logo')) {
                    if ($office->logo && Storage::disk('public')->exists($office->logo)) {
                        Storage::disk('public')->delete($office->logo);
                    }
        
                    $file = $request->file('logo');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $image = $file->storeAs('organizations', $fileName, 'public');
                } else {
                    $image = $office->logo;
                }

                $office->name           = $request->name;
                $office->short_name     = $request->short_name;
                $office->division_id    = $request->division;
                $office->district_id    = $request->district;
                $office->upazila_id     = $request->upazila;
                $office->website_url    = $request->website_url;
                $office->logo           = $image;
                $office->updated_by     = $user->id;
                $office->status         = $request->status ?? 0;

                $office->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Organization Information Updated Successfully!',
                ]);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred. Please try again.',
                ], 500);
            }
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function delete($id)
    {
        $user = Auth::user();

        if(Gate::allows('delete_office', $user)){
            $id = Crypt::decryptString($id);
            $office = Office::where('id', $id)->first();

            $office->status = 2;

            $office->save();

            return response()->json([
                    'success' => true,
                    'message' => 'Organization Deleted Successfully!',
                ]);
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}
