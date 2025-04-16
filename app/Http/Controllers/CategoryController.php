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
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('manage_category', $user)) {
            $parents = Category::where('status', '!=', 2)->orderBy('category_name', 'asc')->get();

            $query = Category::query();

            if (isset($request->category_id) && $request->category_id != '') {
                $query->where('id', $request->category_id);
            }

            $categorys = $query->where('status', '!=', 2)->latest()->get();
            
            if ($request->ajax()) {
                $html = view('backend.admin.category.table', compact('categorys'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }

            return view('backend.admin.category.index', compact('categorys'));
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

        if (Gate::allows('create_category', $user)) {
            $menu_expand = route('admin.category.index');

            $categorys = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
            
            return view('backend.admin.category.create', compact('menu_expand', 'categorys'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('create_category', $user)) {
                $validator = Validator::make($request->all(), [
                    'category_name' => 'required',
                ]);
            
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation Error!',
                        'errors'  => $validator->errors()
                    ], 422);
                }
                
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $image = $file->storeAs('categories', $fileName, 'public');
                } else {
                    $image = null;
                }

                $category = new Category;

                $category->sl               = $request->sl;
                $category->parent_id        = $request->parent_id;
                $category->category_name    = $request->category_name;
                $category->description      = $request->description;
                $category->image            = $image;
                $category->created_by       = $user->id;
                $category->status           = $request->status ?? 0;

                $category->save();

                return response()->json([
                    'success' => true,
                    'message' => 'New Category Created Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission..!",
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        if (Gate::allows('view_category', $user)) {
            $menu_expand = route('admin.category.index');

            $id = Crypt::decryptString($id);
            $category = Category::where('id', $id)->first();
            
            return view('backend.admin.category.show', compact('category', 'menu_expand'));
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

        if (Gate::allows('edit_category', $user)) {
            $menu_expand = route('admin.category.index');

            $id = Crypt::decryptString($id);
            $category = Category::where('id', $id)->first();

            $categorys = Category::where('status', 1)->orderBy('category_name', 'asc')->get();
            
            return view('backend.admin.category.edit', compact('category', 'menu_expand', 'categorys'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('edit_category', $user)) {
                $validator = Validator::make($request->all(), [
                    'category_name' => 'required',
                ]);
            
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation Error!',
                        'errors'  => $validator->errors()
                    ], 422);
                }

                $id = Crypt::decryptString($id);
                $category = Category::where('id', $id)->first();
                
                if ($request->hasFile('image')) {
                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }

                    $file = $request->file('image');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $image = $file->storeAs('categories', $fileName, 'public');
                } else {
                    $image = $category->image;
                }

                $category->sl               = $request->sl;
                $category->parent_id        = $request->parent_id;
                $category->category_name    = $request->category_name;
                $category->description      = $request->description;
                $category->image            = $image;
                $category->updated_by       = $user->id;
                $category->status           = $request->status ?? 0;

                $category->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Category Information Updated Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission..!",
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $user = Auth::user();

        if (Gate::allows('delete_category', $user)) {
            $id = Crypt::decryptString($id);
            $category = Category::where('id', $id)->first();

            $category->status = 2;

            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Category Deleted Successfully!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission..!",
            ], 500);
        }
    }
}
