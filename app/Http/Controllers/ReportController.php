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
use App\Models\Project;
use App\Models\Document;
use App\Models\ProjectInfo;
use App\Models\ProjectValue;
use App\Models\ProjectCategory;
use App\Models\ProjectTransaction;
use App\Models\LeaveCategory;
use App\Models\Leave;
use App\Models\UserCategory;

/* included exports */
use App\Exports\ExportData;
use App\Exports\LeaveData;
use App\Exports\PlReport;

class ReportController extends Controller
{
    public function projectReport(Request $request) 
    {
        $user = Auth::user();

        $categorys = ProjectCategory::where('status', 1)->orderBy('name')->get();
        $clients = User::where('user_type', 7)->where('status', 1)->orderBy('name_en')->get();
        $pms = User::where('user_type', 3)->where('status', 1)->orderBy('name_en')->get();

        if (Gate::allows('project_report', $user)) {
            $query = Project::latest();

            if (isset($request->name) and $request->name != '') {
                $query->where('name', 'like', '%'.$request->name.'%');
            }

            if (isset($request->category_id) and $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            if (isset($request->pm_id) and $request->pm_id != '') {
                $query->where('pm_id', $request->pm_id);
            }

            if (isset($request->client_id) and $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }
            
            $projects = $query->get();

            return view('backend.admin.report.projectReport', compact('projects', 'clients', 'pms', 'categorys'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function exportProjectSummary(Request $request) 
    {
        return Excel::download(new ExportData($request->all()), 'project.xlsx');
    }

    public function exportDocProjectSummary(Request $request) 
    {
        $query = Project::latest();

        $data = $request->all();

        if ($data) {
            if (isset($data['name']) and $data['name'] != '') {
                $query->where('name', 'like', '%'.$data['name'].'%');
            }

            if (isset($data['category_id']) and $data['category_id'] != '') {
                $query->where('category_id', $data['category_id']);
            }

            if (isset($data['pm_id']) and $data['pm_id'] != '') {
                $query->where('pm_id', $data['pm_id']);
            }

            if (isset($data['client_id']) and $data['client_id'] != '') {
                $query->where('client_id', $data['client_id']);
            }
        }

        $projects = $query->get();

        $global_setting = \App\Models\Setting::oldest()->first();

        $image = public_path('/storage/logo/'.( $global_setting->logo ?? '' ).'');

        $headers = array(
            "Content-type"=>"text/html",
    
            "Content-Disposition"=>"attachment;Filename=ProjectSummary.doc"
        );
        
        return \Response::make(view('backend.admin.report.tableData.exportDocProjectSummary', compact('projects', 'image')), 200, $headers);
    }

    public function showPL($id)
    {
        $user = Auth::user();
        
        if (Gate::allows('view_pl_report', $user)) {
            $menu_expand = route('admin.report.projectReport');
            
            $id = Crypt::decryptString($id);
            $project = Project::where('id', $id)->first();
            $project_value = ProjectValue::where('project_id', $id)->first();
            $projectEarnings = ProjectTransaction::where('project_id', $id)->where('type', 1)->get();
            $projectExpenses = ProjectTransaction::where('project_id', $id)->where('type', 2)->get();

            return view('backend.admin.report.showPL', compact('menu_expand', 'project', 'project_value', 'projectEarnings', 'projectExpenses'));
        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function leaveReport(Request $request) 
    {
        $user = Auth::user();

        $categorys = LeaveCategory::where('status', 1)->orderBy('name_en')->get();
        $employees = User::where('user_type', 3)->where('status', 1)->orderBy('name_en')->get();

        if (Gate::allows('leave_report', $user)) {
            $query = Leave::query();

            if (isset($request->employee_id) && $request->employee_id != '') {
                $query->whereHas('userInfo', function($query2) use ($request) {
                    $query2->where('employee_id', $request->employee_id);
                });
            }

            if (isset($request->category_id) && $request->category_id != '') {
                $query->where('leave_category_id', $request->category_id);
            }

            if (isset($request->user_id) && $request->user_id != '') {
                $query->where('user_id', $request->user_id);
            }

            if ($request->from_date && $request->from_date != '') {
                $query->whereDate('from_date', '>=', $request->from_date);
            } else {
                $query->whereYear('from_date', date('Y'));
            }
            
            if ($request->to_date && $request->to_date != '') {
                $query->whereDate('to_date', '<=', $request->to_date);
            } else {
                $query->whereYear('to_date', date('Y'));
            }
            
            $leaves = $query->get();

            $totalLeaves = LeaveCategory::where('status', 1)->sum('day_number');

            return view('backend.admin.report.leaveReport', compact('leaves', 'categorys', 'employees', 'totalLeaves'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }

    public function excelExportLeaveReport(Request $request) 
    {
        return Excel::download(new LeaveData($request->all()), 'leaveReport.xlsx');
    }

    public function exportDocLeaveReport(Request $request) 
    {
        $global_setting = \App\Models\Setting::oldest()->first();

        $image = public_path('/storage/logo/'.( $global_setting->logo ?? '' ).'');

        $query = Leave::latest();
        
        if (isset($request->employee_id) and $request->employee_id != '') {
            $query->whereHas('userInfo', function($query2) use ($request) {
                $query2->where('employee_id', $request->employee_id);
            });
        }

        if (isset($request->category_id) && $request->category_id != '') {
            $query->where('leave_category_id', $request->category_id);
        }

        if (isset($request->user_id) && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->from_date && $request->from_date != '') {
            $query->whereDate('from_date', '>=', $request->from_date);
        } else {
            $query->whereYear('from_date', date('Y'));
        }
        
        if ($request->to_date && $request->to_date != '') {
            $query->whereDate('to_date', '<=', $request->to_date);
        } else {
            $query->whereYear('to_date', date('Y'));
        }
        
        $leaves = $query->get();

        $totalLeaves = LeaveCategory::where('status', 1)->sum('day_number');

        $headers = array(
            "Content-type"=>"text/html",
    
            "Content-Disposition"=>"attachment;Filename=ProjectSummary.doc"
        );
        
        return \Response::make(view('backend.admin.report.tableData.exportDocLeaveReport', compact('leaves', 'image','totalLeaves')), 200, $headers);
    }
    
    public function employeeCategory(Request $request) 
    {
        $user = Auth::user();

        if (Gate::allows('employee_category_report', $user)) {
            $categories = UserCategory::where('status', 1)->withCount(['employeeInfo' => function ($query) {
                $query->where('status', 1);
            }])->get();

            $menu_expand = route('admin.report.employeeCategory');

            return view('backend.admin.report.employeeCategory', compact('categories', 'menu_expand'));

        } else {
            return abort(403, "You don't have permission..!");
        }
    }
    
    public function excelExportPlReport(Request $request, $id) 
    {
        $id = Crypt::decryptString($id);

        return Excel::download(new PlReport($id), 'exportPLReport.xlsx');
    }

    public function exportDocPlReport($id) 
    {
        $id = Crypt::decryptString($id);
        $project = Project::where('id', $id)->first();
        $project_value = ProjectValue::where('project_id', $id)->first();
        $projectEarnings = ProjectTransaction::where('project_id', $id)->where('type', 1)->get();
        $projectExpenses = ProjectTransaction::where('project_id', $id)->where('type', 2)->get();

        $global_setting = \App\Models\Setting::oldest()->first();

        $image = public_path('/storage/logo/'.( $global_setting->logo ?? '' ).'');

        $headers = array(
            "Content-type"=>"text/html",
    
            "Content-Disposition"=>"attachment; Filename=PLReport.doc"
        );
        
        return \Response::make(view('backend.admin.report.tableData.exportDocPL', compact('project', 'image', 'project_value', 'projectEarnings', 'projectExpenses')), 200, $headers);
    }
}
