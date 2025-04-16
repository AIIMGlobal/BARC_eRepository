<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

/* included models */
use App\Models\eTicketType;
use App\Models\User;

class ETicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('all_eTicket_type', $user)) {
            $query = eTicketType::query();

            if (isset($request->title) && $request->title != '') {
                $query->where('title', 'like', '%'.$request->title.'%');
            }
            
            $types = $query->where('status', '!=', 2)->orderBy('sl')->latest()->paginate(20);

            return view('backend.admin.eTicketType.index', compact('types'));
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

        if (Gate::allows('add_eTicket_type', $user)) {
            $menu_expand = route('admin.eTicketType.index');

            return view('backend.admin.eTicketType.create', compact('menu_expand'));
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
        
        if (Gate::allows('add_eTicket_type', $user)) {
            $this->validate($request, [
                'title' => 'required',
            ]);

            $type =  new eTicketType;

            $type->sl           = $request->sl;
            $type->title        = $request->title;
            $type->description  = $request->description;
            $type->status       = $request->status ?? 0;
            $type->created_by   = Auth::id();

            $type->save();

            return redirect()->back()->with('success', "New E-Ticket Type added successfully!");
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

        if (Gate::allows('show_eTicket_type', $user)) {
            $menu_expand = route('admin.eTicketType.index');

            $id = Crypt::decryptString($id);
            $type = eTicketType::where('id', $id)->first();

            return view('backend.admin.eTicketType.show', compact('menu_expand', 'type'));
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

        if (Gate::allows('edit_eTicket_type', $user)) {
            $menu_expand = route('admin.eTicketType.index');

            $id = Crypt::decryptString($id);
            $type = eTicketType::where('id', $id)->first();

            return view('backend.admin.eTicketType.edit', compact('menu_expand', 'type'));
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
        
        if (Gate::allows('edit_eTicket_type', $user)) {
            $this->validate($request, [
                'title' => 'required',
            ]);

            $id = Crypt::decryptString($id);
            $type = eTicketType::where('id', $id)->first();

            $type->sl           = $request->sl;
            $type->title        = $request->title;
            $type->description  = $request->description;
            $type->status       = $request->status ?? 0;
            $type->updated_by   = Auth::id();

            $type->save();

            return redirect()->back()->with('success', "E-Ticket Type added successfully!");
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

        if (Gate::allows('delete_eTicket_type', $user)) {
            $id = Crypt::decryptString($id);
            $type = eTicketType::where('id', $id)->first();

            $type->status = 2;

            $type->save();

            return redirect()->route('admin.eTicketType.index')->with('success', 'E-Ticket deleted successfully!');
        } else {
            return abort(403, "You don't have permission!");
        }
    }
}
