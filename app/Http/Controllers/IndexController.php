<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;
use Session;
use Illuminate\Support\Facades\App;
use Auth;
use Illuminate\Support\Facades\DB;

/* included models */
use App\Models\User;
use App\Models\Project;
use App\Models\Document;
use App\Models\District;
use App\Models\Upazila;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $now =  date('Y-m-d');
        $year =  date('Y');
        $projectsCount = Project::count();
        $documentsCount = Document::where('type', 1)->count();
        $clientsCount = User::where('user_type', 7)->where('status', 1)->count();
        $employeesCount = User::where('user_type', 3)->where('status', 1)->count();
        $usersCount = User::where('user_type', 4)->where('status', 1)->count();

        $latestProjects = Project::where('end_date', '>=', $now)->orderByRaw('ISNULL(end_date), end_date ASC')->take(5)->get();

        $query = Project::query();

        if ($request->from_date || $request->to_date) {
            if ($request->from_date && $request->from_date != '') {
                $query->whereDate('start_date', '>=', $request->from_date);
            }
            
            if ($request->to_date && $request->to_date != '') {
                $query->whereDate('start_date', '<=', $request->to_date);
            }
        } else {
            $query->latest()->take(10);
        }

        $projects = $query->get();

        return view('backend.index', compact('employeesCount', 'projectsCount', 'clientsCount', 'documentsCount', 'latestProjects', 'projects', 'usersCount'));
    }

    public function getDistrictsAJAX(Request $request)
    {
        $data = $request->all();
        $districts = District::where('division_id', $data['division_id'])->select('id', 'name_en')->get();

        return Response::json($districts);
    }

    public function getUpazilasAJAX(Request $request)
    {
        $data = $request->all();
        $upazilas = Upazila::where('district_id', $data['district_id'])->select('id', 'name_en')->get();

        return Response::json($upazilas);
    }

    public function language_change(Request $request)
    {
        if (!Session::get('lang')) {
            $request->session()->put('lang','en');
        }

        if (Session::get('lang') == 'en') {
            $request->session()->put('lang','bn');
        } else {
            $request->session()->put('lang','en');
        }

        return back();
    }
}