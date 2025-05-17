<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;
use Response;
Use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

/* included models */
use App\Models\User;
use App\Models\Office;
use App\Models\Upazila;
use App\Models\Content;
use App\Models\District;
use App\Models\Category;
use App\Models\UserContentActivity;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $categorys = Category::where('status', 1)
                                ->withCount(['contents' => function ($query) {
                                    $query->where('status', 1);
                                }])
                                ->orderByDesc('contents_count')
                                ->get();

        $currentYear = date('Y');

        $contents = Content::where('status', 1)
                            ->whereYear('created_at', $currentYear)
                            ->get()
                            ->groupBy(function ($content) {
                                return Carbon::parse($content->created_at)->format('F');
                            })
                            ->map(function ($group) {
                                return $group->count();
                            })
                            ->sortKeys();

        $users = User::with('userInfo')
                        ->where('user_type', 4)
                        ->whereIn('status', [0, 1, 2, 3])
                        ->get();

        $statusMap = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Declined',
            3 => 'Archived'
        ];

        $statusCounts = $users->groupBy('status')->map(function ($group) {
            return $group->count();
        })->mapWithKeys(function ($count, $status) use ($statusMap) {
            return [$statusMap[$status] ?? 'Unknown' => $count];
        })->only(array_values($statusMap));

        $statusCounts = collect($statusMap)->mapWithKeys(function ($label) use ($statusCounts) {
            return [$label => $statusCounts->get($label, 0)];
        });

        $totalUsers = $statusCounts->sum();

        $chartData = [
            'labels' => $statusCounts->keys()->toArray(),
            'counts' => $statusCounts->values()->toArray(),
            'percentages' => $statusCounts->map(function ($count) use ($totalUsers) {
                return $totalUsers > 0 ? round(($count / $totalUsers) * 100, 2) : 0;
            })->values()->toArray()
        ];

        $offices = Office::whereIn('id', $users->pluck('userInfo.office_id')->unique())->pluck('name', 'id');

        $uploadedCount = Content::where('status', 1)->where('created_by', Auth::id())->count();
        $favCount = UserContentActivity::where('activity_type', 1)->where('user_id', Auth::id())->count();
        $savedCount = UserContentActivity::where('activity_type', 2)->where('user_id', Auth::id())->count();
        $employeesCount = User::where('user_type', 3)->where('status', 1)->count();
        $usersCount = User::where('user_type', 4)->where('status', 1)->count();

        return view('backend.index', compact('employeesCount', 'categorys', 'contents', 'usersCount', 'favCount', 'savedCount', 'uploadedCount', 'users', 'offices', 'chartData'));
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