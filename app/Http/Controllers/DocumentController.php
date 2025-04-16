<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

/* included models */
use App\Models\Project;
use App\Models\Document;
use App\Models\User;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_document', $user)) {

            $projects = Project::all();

            $query = Document::query();

            if(isset($request->name) and $request->name != ''){
                $query->where('name', 'like', '%'.$request->name.'%');
            }

            if(isset($request->project_id) and $request->project_id != ''){
                $query->where('project_id', $request->project_id);
            }
            
            if(!Gate::allows('can_view_all_document', $user)){
                $query->whereRaw('FIND_IN_SET(?, user_ids)', [$user->id]);
            }
            
            $documents = $query->where('type', 1)->latest()->paginate(20);

            return view('backend.admin.document.index', compact('documents', 'projects'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function legalIndex(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_legal_document', $user)) {
            $query = Document::query();

            if(isset($request->name) and $request->name != ''){
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            if(!Gate::allows('can_view_all_document', $user)){
                $query->whereRaw('FIND_IN_SET(?, user_ids)', [$user->id]);
            }
            
            $documents = $query->where('type', 2)->latest()->paginate(20);

            return view('backend.admin.document.legalIndex', compact('documents'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function membershipIndex(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_membership_document', $user)) {
            $query = Document::query();

            if(isset($request->name) and $request->name != ''){
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            if(!Gate::allows('can_view_all_document', $user)){
                $query->whereRaw('FIND_IN_SET(?, user_ids)', [$user->id]);
            }
            
            $documents = $query->where('type', 3)->latest()->paginate(20);

            return view('backend.admin.document.membershipIndex', compact('documents'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function financialIndex(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_financial_document', $user)) {
            $query = Document::query();

            if(isset($request->name) and $request->name != ''){
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            
            if(!Gate::allows('can_view_all_document', $user)){
                $query->whereRaw('FIND_IN_SET(?, user_ids)', [$user->id]);
            }
            
            $documents = $query->where('type', 4)->latest()->paginate(20);

            return view('backend.admin.document.financialIndex', compact('documents'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('add_document', $user)) {
            $menu_expand = route('admin.document.index');

            $projects = Project::latest()->select('id', 'name')->get();
            $employees = User::where('user_type', 3)->where('status', 1)->where('id', '!=', $user->id)->get();

            return view('backend.admin.document.create', compact('menu_expand', 'projects', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function legalCreate()
    {
        $user = Auth::user();

        if (Gate::allows('add_legal_document', $user)) {
            $menu_expand = route('admin.document.legalIndex');

            $projects = Project::latest()->select('id', 'name')->get();
            $employees = User::where('user_type', 3)->where('status', 1)->where('id', '!=', $user->id)->get();

            return view('backend.admin.document.legalCreate', compact('menu_expand', 'projects', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function membershipCreate()
    {
        $user = Auth::user();

        if (Gate::allows('add_membership_document', $user)) {
            $menu_expand = route('admin.document.membershipIndex');

            $projects = Project::latest()->select('id', 'name')->get();
            $employees = User::where('user_type', 3)->where('status', 1)->where('id', '!=', $user->id)->get();

            return view('backend.admin.document.membershipCreate', compact('menu_expand', 'projects', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function financialCreate()
    {
        $user = Auth::user();

        if (Gate::allows('add_financial_document', $user)) {
            $menu_expand = route('admin.document.financialIndex');

            $projects = Project::latest()->select('id', 'name')->get();
            $employees = User::where('user_type', 3)->where('status', 1)->where('id', '!=', $user->id)->get();

            return view('backend.admin.document.financialCreate', compact('menu_expand', 'projects', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required',
        ]);

        $user = Auth::user();

        $data = $request->user_ids ?? array();
        array_push($data, $user->id);

        if (Gate::allows('add_document', $user)) {
            if ($request->file) {
                foreach ($request->file as $key => $file) {
                    if ($request->file('file')[$key]) {
                        $path = $request->file('file')[$key]->store('/public/document');
                        $path = Str::replace('public/document', '', $path);
                        $documentFile = Str::replace('/', '', $path);

                        $document['name'] = $request->file[$key]->getClientOriginalName();
                        $document['file'] = $documentFile;
                        $document['type'] = $request->type;
                        $document['project_id'] = $request->project_id;
                        $document['created_by'] = Auth::id();
                        
                        if ($request->user_ids) {
                            $document['user_ids'] = implode(',', $data);
                        }

                        Document::create($document);
                    }
                }
            }
            
            return redirect()->back()->with('success', 'Document stored successfully!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function edit($id)
    {
        $user = Auth::user();

        if(Gate::allows('edit_document', $user)){
            $menu_expand = route('admin.document.index');

            $id = Crypt::decryptString($id);
            $document = Document::where('id', $id)->first();
            $projects = Project::latest()->select('id', 'name')->get();
            $employees = User::where('user_type', 3)->where('status', 1)->where('id', '!=', $user->id)->get();

            return view('backend.admin.document.edit', compact('menu_expand', 'document', 'projects', 'employees'));
        } else {
            return abort(403, "You don't have permission..!");
        }
        
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $documentInfo = Document::where('id', $id)->first();

        if (Gate::allows('edit_document', $user)) {
            $data = $request->user_ids ?? array();
            array_push($data, $user->id);
            
            if ($request->file) {
                if ($documentInfo->file) {
                    $imagePath = public_path(). '/storage/document/' . $documentInfo->file;
    
                    if(($documentInfo->file != '') || ($documentInfo->file != NULL)) {
                        if(file_exists(public_path(). '/storage/document/' . $documentInfo->file)){
                            unlink($imagePath);
                        }
                    }
                }

                $path = $request->file('file')->store('/public/document');
                $path = Str::replace('public/document', '', $path);
                $documentFile = Str::replace('/', '', $path);

                $document['name']       = $request->name;
                $document['file']       = $documentFile;
                $document['type']       = $request->type;
                $document['project_id'] = $request->project_id;
                $document['user_ids']   = implode(',', $data);
                $document['updated_by'] = Auth::id();
            } else {
                $document['name']       = $request->name;
                $document['type']       = $request->type;
                $document['project_id'] = $request->project_id;
                $document['user_ids']   = implode(',', $data);
                $document['updated_by'] = Auth::id();
            }

            Document::where('id', $id)->update($document);

            return redirect()->back()->with('success', 'Document info updated successfully!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function delete($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_document', $user)) {
            $id = Crypt::decryptString($id);
            $documentInfo = Document::where('id', $id)->first();

            if ($documentInfo) {
                $imagePath = public_path(). '/storage/document/' . $documentInfo->file;

                if(($documentInfo->file != '') || ($documentInfo->file != NULL)) {
                    if(file_exists(public_path(). '/storage/document/' . $documentInfo->file)){
                        unlink($imagePath);
                    }
                }
            }

            $documentInfo->delete();

            return redirect()->back()->with('success', 'Document deleted.');

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if(Gate::allows('view_document', $user)){
            $menu_expand = route('admin.document.index');

            $id = Crypt::decryptString($id);
            $document = Document::where('id', $id)->first();

            return view('backend.admin.document.view', compact('menu_expand', 'document'));
        } else {
            return abort(403, "You don't have permission..!");
        }
        
    }

    public function massDelete(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('delete_document', $user)) {
            $ids = $request->document;

            if ($ids != '') {
                $docs = Document::whereIn('id', $ids)->get();

                foreach ($docs as $doc) {
                    if ($doc) {
                        $imagePath = public_path(). '/storage/document/' . $doc->file;
        
                        if(($doc->file != '') || ($doc->file != NULL)) {
                            if(file_exists(public_path(). '/storage/document/' . $doc->file)){
                                unlink($imagePath);
                            }
                        }
                    }
        
                    $doc->delete();
                }
    
                return redirect()->back()->with('success', 'Document Deleted.');
            }
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}
