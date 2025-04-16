<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Board;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if(Gate::allows('manage_board', $user)){
            if(($request->name != '') && ($request->name != NULL)){
                $boards = Board::where('name_en', 'like', '%'.$request->name.'%')->where(function($query){
                    $query->where('status', 0)->orWhere('status', 1);
                })->latest()->paginate(15);
            }else{
                $boards = Board::where('status', 0)->orWhere('status', 1)->latest()->paginate(15);
            }
            return view('backend.admin.academicForm.board.index', compact('boards'));
        }else{
            return abort(403,"You don't have permission..!");
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
        if(Gate::allows('add_board', $user)){
            $menu_expand = route('admin.board.index');
            return view('backend.admin.academicForm.board.create', compact('menu_expand'));
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
        if(Gate::allows('add_board', $user)){
            $this->validate($request, [
                'name_en' => 'required',
            ]);

            $board = new Board;
            $board->sl = $request->sl;
            $board->name_en = $request->name_en;
            $board->status = $request->status ?? 0;
            $board->created_by = $user->id;
            $board->save();
            return redirect()->route('admin.board.index')->with('success', 'New Board added successfully..!');

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
        if(Gate::allows('edit_board', $user)){  
            $this->validate($request, [
                'name_en' => 'required',
            ]);
            $board = Board::where('id', $id)->first();
            $board->sl = $request->sl;
            $board->name_en = $request->name_en;
            $board->status = $request->status ?? 0;
            $board->updated_by = $user->id;
            $board->save();
            return redirect()->route('admin.board.index')->with('success', 'Board updated successfully..!');
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
        if(Gate::allows('delete_board', $user)){
            $board = Board::where('id', $id)->first();
            $board->status = 2;
            $board->save();

            return redirect()->route('admin.board.index')->with('success', 'Board deleted successfully..!');

        }else{
            return abort(403, "You don't have permission..!");
        }
    }
}
