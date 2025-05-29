<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

/* included models */
use App\Models\User;
use App\Models\Office;
use App\Models\Designation;
use App\Models\UserCategory;
use App\Models\Category;
use App\Models\Content;

class ReportController extends Controller
{
    public function orgUserReport(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('organization_user_report', $user)) {
            $orgs = Office::where('status', 1)->get();
            $designations = Designation::where('status', 1)->get();
            $categorys = UserCategory::where('status', 1)->get();

            if ($request->ajax()) {
                $query = User::query()->where('user_type', 4)->where('status', 1);

                if ($request->organization) {
                    $query->whereHas('userInfo', function($query2) use($request) {
                        $query2->where('office_id', $request->organization);
                    });
                }

                if ($request->designation) {
                    $query->whereHas('userInfo', function($query2) use($request) {
                        $query2->where('designation_id', $request->designation);
                    });
                }

                if ($request->service_type) {
                    $query->where('user_category_id', $request->service_type);
                }

                $reports = $query->get();

                if ($reports->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'html' => '<tr><td colspan="6" class="text-center">No data found</td></tr>'
                    ]);
                }

                $html = view('backend.admin.report.orgUserTable', compact('reports'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html
                ]);
            }

            return view('backend.admin.report.orgUser', compact('orgs', 'designations', 'categorys'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function orgContentReport(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('organization_content_report', $user)) {
            $orgs = Office::where('status', 1)->get();
            $categorys = Category::where('status', 1)->get();
            $users = User::where('user_type', 4)->where('status', 1)->get();

            if ($request->ajax()) {
                $query = Content::query();

                if ($request->organization) {
                    $query->whereHas('createdBy.userInfo', function($query2) use($request) {
                        $query2->where('office_id', $request->organization);
                    });
                }

                if ($request->category) {
                    $query->where('category_id', $request->category);
                }

                if ($request->user_id) {
                    $query->where('created_by', $request->user_id);
                }

                $reports = $query->where('status', 1)->with(['createdBy.userInfo.office', 'createdBy.userInfo.post'])->get();

                $totalCount = $reports->count();

                if ($reports->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'html' => '<tr><td colspan="7" class="text-center">No data found</td></tr>',
                        'totalCount' => 0
                    ]);
                }

                $html = view('backend.admin.report.orgContentTable', compact('reports'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalCount' => $totalCount
                ]);
            }

            return view('backend.admin.report.orgContent', compact('orgs', 'users', 'categorys', 'request'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function contentReport(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('content_report', $user)) {
            $categorys = Category::where('status', 1)->get();
            $contentTypes = ['Video', 'PDF', 'Audio', 'Image', 'Link', 'Other'];

            if ($request->ajax()) {
                $query = Content::query();

                if ($request->category_id) {
                    $query->where('category_id', $request->category_id);
                }

                if ($request->content_type) {
                    $query->where('content_type', $request->content_type);
                }

                if ($request->from_date) {
                    $query->whereDate('published_at', '>=', $request->from_date);
                }

                if ($request->to_date) {
                    $query->whereDate('published_at', '<=', $request->to_date);
                }

                if (Auth::user()->user_type == 4) {
                    $reports = $query->where('created_by', $user->id)->where('status', 1)->with(['createdBy.userInfo.office', 'category'])->get();
                } else {
                    $reports = $query->where('status', 1)->with(['createdBy.userInfo.office', 'category'])->get();
                }

                $totalCount = $reports->count();

                if ($reports->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'html' => '<tr><td colspan="8" class="text-center">No data found</td></tr>',
                        'totalCount' => 0
                    ]);
                }

                $html = view('backend.admin.report.contentReportTable', compact('reports'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'totalCount' => $totalCount
                ]);
            }

            return view('backend.admin.report.contentReport', compact('categorys', 'contentTypes', 'request'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }
}