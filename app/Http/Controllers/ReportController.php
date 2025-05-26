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
}