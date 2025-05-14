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
use App\Models\Content;
use App\Models\Category;
use App\Models\UserContentActivity;

class ContentController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('manage_content', $user)) {
            $categories = Category::where('status', '!=', 2)->orderBy('category_name', 'asc')->get();

            $query = Content::query();

            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->content_id) {
                $query->where('id', $request->content_id);
            }

            if ($request->content_type) {
                $query->where('content_type', $request->content_type);
            }

            if ($request->from_date && $request->to_date) {
                $query->whereBetween('published_at', [$request->from_date, $request->to_date]);
            }

            $perPage = $request->per_page ?? 24;
            
            $contents = $query->where('status', 1)
                            ->with(['category', 'createdBy'])
                            ->latest()
                            ->paginate($perPage);

            if ($request->ajax()) {
                $html = view('backend.admin.content.content', compact('contents'))->render();

                $hasMore = $contents->hasMorePages();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'hasMore' => $hasMore,
                ]);
            }

            return view('backend.admin.content.index', compact('contents', 'categories'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    public function indexMyContent(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('manage_content', $user)) {
            $categories = Category::where('status', '!=', 2)
                                    ->orderBy('category_name', 'asc')
                                    ->get();

            $query = Content::query();

            if ($request->category_id) {
                $category = Category::find($request->category_id);

                if ($category) {
                    $categoryIds = [$category->id];

                    $this->getChildCategoryIds($category, $categoryIds);

                    $query->whereIn('category_id', $categoryIds);
                }
            }

            if ($request->content_name) {
                $query->where('content_name', 'like', '%' . $request->content_name . '%');
            }

            if ($request->content_type) {
                $query->where('content_type', $request->content_type);
            }

            if ($request->from_date && $request->to_date) {
                $fromDate = \Carbon\Carbon::parse($request->from_date)->startOfDay();
                $toDate = \Carbon\Carbon::parse($request->to_date)->endOfDay();
                $query->whereBetween('published_at', [$fromDate, $toDate]);
            } elseif ($request->from_date) {
                $fromDate = \Carbon\Carbon::parse($request->from_date)->startOfDay();
                $query->where('published_at', '>=', $fromDate);
            } elseif ($request->to_date) {
                $toDate = \Carbon\Carbon::parse($request->to_date)->endOfDay();
                $query->where('published_at', '<=', $toDate);
            }

            $perPage = $request->per_page ?? 24;

            $contents = $query->where('status', '!=', 2)
                                ->with([
                                    'category',
                                    'createdBy',
                                    'userActivities' => fn($q) => $q->where('user_id', Auth::id())
                                ])
                                ->latest('published_at')
                                ->paginate($perPage);

            if ($request->ajax()) {
                $html = view('backend.admin.content.content', compact('contents'))->render();
                $hasMore = $contents->hasMorePages();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'hasMore' => $hasMore,
                ]);
            }

            return view('backend.admin.content.indexMyContent', compact('contents', 'categories'));
        } else {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission!"
            ], 403);
        }
    }

    /**
     * Recursively collect child category IDs
     */
    private function getChildCategoryIds($category, &$categoryIds)
    {
        foreach ($category->children as $child) {
            $categoryIds[] = $child->id;

            $this->getChildCategoryIds($child, $categoryIds);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (Gate::allows('create_content', $user)) {
            $menu_expand = route('admin.content.create');

            $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();

            return view('backend.admin.content.create', compact('menu_expand', 'categories'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('create_content', $user)) {
                $validator = Validator::make($request->all(), [
                    'content_name'  => 'required',
                    'content'       => 'required',
                    'content_type'  => 'required',
                    'category_id'   => 'required|exists:categories,id',
                    'content_year'  => 'required|digits:4',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation Error!',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $slug = \Str::slug($request->content_name);

                $count = Content::where('slug', $slug)->count();

                if ($count > 0) {
                    $slug = $slug . '-' . time();
                }

                if ($request->hasFile('thumbnail')) {
                    $file = $request->file('thumbnail');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $thumbnail = $file->storeAs('thumbnails', $fileName, 'public');
                } else {
                    $thumbnail = null;
                }

                if ($request->hasFile('content')) {
                    $filecontent = $request->file('content');
                    $fileNamecontent = time() . '_' . $filecontent->getClientOriginalName();
                    $extension = $filecontent->getClientOriginalExtension();
                    $contentPath = $filecontent->storeAs('contents', $fileNamecontent, 'public');
                } else {
                    $contentPath = null;
                }

                $content = new Content;

                $content->sl                = $request->sl;
                $content->category_id       = $request->category_id;
                $content->content_type      = $request->content_type;
                $content->content_name      = $request->content_name;
                $content->slug              = $slug;
                $content->description       = $request->description;
                $content->extension         = $extension ?? '';
                $content->content           = $contentPath;
                $content->content_year      = $request->content_year;
                $content->thumbnail         = $thumbnail;
                $content->meta_title        = $request->meta_title;
                $content->meta_description  = $request->meta_description;
                $content->meta_keywords     = $request->meta_keywords;
                $content->status            = $request->status ?? 0;
                $content->created_by        = $user->id;
                $content->published_at      = $request->status == 1 ? now() : null;

                // if ($request->status == 1) {
                //     $content->approved_by = $user->id;

                //     $content->approved_at = now();
                // }

                $content->save();

                return response()->json([
                    'success' => true,
                    'message' => 'New Content Created Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
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

        if (Gate::allows('view_content', $user)) {
            $menu_expand = route('admin.content.index');

            $id = Crypt::decryptString($id);
            $content = Content::where('id', $id)->firstOrFail();

            return view('backend.admin.content.show', compact('content', 'menu_expand'));
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

        if (Gate::allows('edit_content', $user)) {
            $menu_expand = route('admin.content.index');

            $id = Crypt::decryptString($id);
            $content = Content::where('id', $id)->firstOrFail();

            $categories = Category::where('status', 1)->orderBy('category_name', 'asc')->get();

            return view('backend.admin.content.edit', compact('content', 'menu_expand', 'categories'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);
            $content = Content::where('id', $id)->firstOrFail();

            $user = Auth::user();

            if (Gate::allows('edit_content', $user)) {
                $validator = Validator::make($request->all(), [
                    'content_name'  => 'required',
                    'content_type'  => 'required',
                    'category_id'   => 'required|exists:categories,id',
                    'content_year'  => 'required|digits:4',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation Error!',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $slug = \Str::slug($request->content_name);
                $count = Content::where('slug', $slug)->where('id', '!=', $content->id)->count();

                if ($count > 0) {
                    $slug = $slug . '-' . time();
                }

                if ($request->hasFile('thumbnail')) {
                    if ($content->thumbnail) {
                        \Storage::disk('public')->delete($content->thumbnail);
                    }

                    $file = $request->file('thumbnail');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $thumbnail = $file->storeAs('thumbnails', $fileName, 'public');
                } else {
                    $thumbnail = $content->thumbnail;
                }

                if ($request->hasFile('content')) {
                    if ($content->content) {
                        \Storage::disk('public')->delete($content->content);
                    }

                    $filecontent = $request->file('content');
                    $fileNamecontent = time() . '_' . $filecontent->getClientOriginalName();
                    $extension = $filecontent->getClientOriginalExtension();
                    $contentPath = $filecontent->storeAs('contents', $fileNamecontent, 'public');
                } else {
                    $contentPath = $content->content;
                    $extension = $content->extension;
                }

                $content->sl                = $request->sl;
                $content->category_id       = $request->category_id;
                $content->content_type      = $request->content_type;
                $content->content_name      = $request->content_name;
                $content->slug              = $slug;
                $content->description       = $request->description;
                $content->extension         = $extension ?? $content->extension;
                $content->content           = $contentPath;
                $content->content_year      = $request->content_year;
                $content->thumbnail         = $thumbnail;
                $content->meta_title        = $request->meta_title;
                $content->meta_description  = $request->meta_description;
                $content->meta_keywords     = $request->meta_keywords;
                $content->status            = $request->status ?? 0;
                $content->updated_by        = $user->id;
                $content->published_at      = $request->status == 1 ? ($content->published_at ?? now()) : null;

                $content->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Content Details Updated Successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
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
    public function destroy($id)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('delete_content', $user)) {
                $id = Crypt::decryptString($id);
                $content = Content::where('id', $id)->firstOrFail();

                if ($content) {
                    $contentExist = UserContentActivity::where('content_id', ($content->id ?? 0))->delete();

                    if ($content->content) {
                        \Storage::disk('public')->delete($content->content);
                    }
                    
                    $content->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Content Deleted Successfully!',
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Content Not Found!',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
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
     * Archive content
     */
    public function archive($id)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('archive_content', $user)) {
                $id = Crypt::decryptString($id);
                $content = Content::where('id', $id)->firstOrFail();
                
                if ($content->status == 3) {
                    $content->status        = 0;

                    $content->updated_by    = $user->id;

                    $content->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Content Unarchived Successfully!',
                    ]);
                } else {
                    $content->status        = 3;

                    $content->updated_by    = $user->id;

                    $content->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Content Archived Successfully!',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
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
     * Toggle favorite status
     */
    public function toggleFavorite(Request $request, $id)
    {
        try {
            $user = Auth::user();

            if (Gate::allows('manage_content', $user)) {
                $id = Crypt::decryptString($id);
                $content = Content::findOrFail($id);

                $contentExist = UserContentActivity::where('user_id', Auth::id())->where('content_id', ($content->id ?? 0))->where('activity_type', 1)->first();

                if (!$contentExist) {
                    UserContentActivity::create([
                        'user_id' => $user->id,
                        'category_id' => $content->category_id,
                        'content_id' => $content->id,
                        'activity_type' => 1,
                        
                        'created_by' => $user->id,
                    ]);

                    $message = 'Content added to favorites!';
                } else {
                    $contentExist->delete();

                    $message = 'Content removed from favorites!';
                }

                return response()->json([
                    'success' => true,
                    'is_favorite' => $contentExist,
                    'message' => $message,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    /**
     * Toggle save status
     */
    public function toggleSave(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (Gate::allows('manage_content', $user)) {
                $id = Crypt::decryptString($id);
                $content = Content::findOrFail($id);

                $contentExist = UserContentActivity::where('user_id', Auth::id())->where('content_id', ($content->id ?? 0))->where('activity_type', 2)->first();

                if (!$contentExist) {
                    UserContentActivity::create([
                        'user_id' => $user->id,
                        'category_id' => $content->category_id,
                        'content_id' => $content->id,
                        'activity_type' => 2,
                        'created_by' => $user->id,
                    ]);

                    $message = 'Content saved successfully!';
                } else {
                    $contentExist->delete();

                    $message = 'Content removed from saved!';
                }

                return response()->json([
                    'success' => true,
                    'is_saved' => $contentExist,
                    'message' => $message,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You don't have permission!",
                ], 403);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    /**
     * Bulk delete or archive contents
     */
    // public function bulkAction(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         if (Gate::allows('manage_content', $user)) {
    //             $validator = Validator::make($request->all(), [
    //                 'ids'       => 'required|array',
    //                 'action'    => 'required|in:delete,archive',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Validation Error!',
    //                     'errors' => $validator->errors()
    //                 ], 422);
    //             }

    //             $status = $request->action === 'delete' ? 2 : 3;

    //             Content::whereIn('id', $request->ids)->update([
    //                 'status'        => $status,
    //                 'updated_by'    => $user->id,
    //             ]);

    //             $message = $request->action === 'delete' ? 'Contents Deleted Successfully!' : 'Contents Archived Successfully!';

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $message,
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "You don't have permission!",
    //             ], 403);
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error($e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred. Please try again.',
    //         ], 500);
    //     }
    // }
}