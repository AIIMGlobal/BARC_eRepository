<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


/* included models */
use App\Models\ProjectTransaction;
use App\Models\Project;

class ProjectTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_project_transaction', $user)) {
            $projects = Project::all();

            $query = ProjectTransaction::latest();

            if (isset($request->project_id) and $request->project_id != '') {
                $query->where('project_id', $request->project_id);
            }
            
            $projectTransactions = $query->paginate(20);

            return view('backend.admin.projectTransaction.index', compact('projectTransactions', 'projects'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('create_project_transaction', $user)) {
            $menu_expand = route('admin.project_transaction.index');

            $projects = Project::all();

            return view('backend.admin.projectTransaction.create', compact('menu_expand', 'projects'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (Gate::allows('create_project_transaction', $user)) {
            DB::transaction(function () use ($request, $user) {
                $request->validate([
                    'project_id'           => 'required',
                ]);

                $project_id = $request->project_id;
                $type       = $request->input('type', []);
                $purpose    = $request->input('purpose', []);
                $amount     = $request->input('amount', []);
                $document   = $request->input('document', []);

                $transactionInfo    = [];

                foreach ($request->type as $index => $unit) {
                    $attach = NULL;

                    if ($request->file('document')[$index] ?? '') {
                        $path = $request->file('document')[$index]->store('/public/transactionDocument');

                        $path = Str::replace('public/transactionDocument', '', $path);

                        $attach = Str::replace('/', '', $path);
                    }

                    $transactionInfo[] = [
                        "project_id"    => $project_id,
                        "type"          => $type[$index],
                        "purpose"       => $purpose[$index],
                        "amount"        => $amount[$index],
                        "document"      => $attach ?? NULL,
                        "created_by"    => $user->id,
                        "created_at"    => date('Y-m-d'),
                        "updated_at"    => date('Y-m-d'),
                    ];
                }

                ProjectTransaction::insert($transactionInfo);
            });

            return redirect()->back()->with('success', "New project transaction added successfully..!");
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        if (Gate::allows('show_project_transaction', $user)) {
            $menu_expand = route('admin.project_transaction.index');

            $id = Crypt::decryptString($id);
            $projectTransaction = ProjectTransaction::where('id', $id)->first();

            return view('backend.admin.projectTransaction.show', compact('menu_expand', 'projectTransaction'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_project_transaction', $user)) {
            $menu_expand = route('admin.project_transaction.index');

            $id = Crypt::decryptString($id);
            $projects = Project::all();
            $projectTransaction = ProjectTransaction::where('id', $id)->first();

            return view('backend.admin.projectTransaction.edit', compact('menu_expand', 'projectTransaction', 'projects'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (Gate::allows('edit_project_transaction', $user)) {
            $projectTransaction = ProjectTransaction::where('id', $id)->first();

            $request->validate([
                'project_id'    => 'required',
                'type'          => 'required',
                'purpose'       => 'required',
                'amount'        => 'required',
            ]);

            $projectTransaction->project_id   = $request->project_id;
            $projectTransaction->type         = $request->type;
            $projectTransaction->purpose      = $request->purpose;
            $projectTransaction->amount       = $request->amount;
            $projectTransaction->updated_by   = $user->id;

            if($request->document){
                $f = 'transactionDocument/' . $projectTransaction->document;
                    
                if ($f) {
                    if(Storage::disk('public')->exists($f))
                    {
                        Storage::disk('public')->delete($f);
                    }
                }

                $path = $request->file('document')->store('/public/transactionDocument');

                $path = Str::replace('public/transactionDocument', '', $path);

                $projectTransaction->document = Str::replace('/', '', $path);
            }

            $projectTransaction->save();

            return redirect()->route('admin.project_transaction.index')->with('success', 'Project updated successfully.');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (Gate::allows('delete_project_transaction', $user)) {
            $id = Crypt::decryptString($id);
            
            ProjectTransaction::where('id', $id)->delete();

            return redirect()->route('admin.project_transaction.index')->with('success', 'Deleted successfully..!');
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}
