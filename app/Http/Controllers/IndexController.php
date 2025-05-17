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

        $contentCount = Content::where('status', 1)->count();

        $users = User::with('userInfo')
            ->where('user_type', 4)
            ->whereIn('status', [0, 1, 2, 4])
            ->get();

        // Define status mapping
        $statusMap = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Declined',
            4 => 'Archived'
        ];

        // Group users by status and office_id
        $statusOfficeCounts = $users->groupBy('status')->map(function ($statusGroup) {
            return $statusGroup->groupBy('userInfo.office_id')->map->count();
        })->mapWithKeys(function ($officeCounts, $status) use ($statusMap) {
            return [$statusMap[$status] ?? 'Unknown' => $officeCounts];
        })->only(array_values($statusMap));

        // Ensure all statuses are present (even if empty)
        $statusOfficeCounts = collect($statusMap)->mapWithKeys(function ($label) use ($statusOfficeCounts) {
            return [$label => $statusOfficeCounts->get($label, collect())];
        });

        // Calculate total counts per status
        $statusCounts = $statusOfficeCounts->map(function ($officeCounts) {
            return $officeCounts->sum();
        });

        // Calculate total users for percentages
        $totalUsers = $statusCounts->sum();

        // Fetch office names
        $officeIds = $users->pluck('userInfo.office_id')->unique()->filter();
        $offices = Office::whereIn('id', $officeIds)->pluck('name', 'id')->toArray();

        // Prepare chart data
        $chartData = [
            'labels' => $statusCounts->keys()->toArray(), // ['Pending', 'Approved', 'Declined', 'Archived']
            'counts' => $statusCounts->values()->toArray(),
            'percentages' => $statusCounts->map(function ($count) use ($totalUsers) {
                return $totalUsers > 0 ? round(($count / $totalUsers) * 100, 2) : 0;
            })->values()->toArray(),
            'office_counts' => $statusOfficeCounts->map(function ($officeCounts) use ($offices) {
                return $officeCounts->mapWithKeys(function ($count, $officeId) use ($offices) {
                    $officeName = $offices[$officeId] ?? 'Unknown Office';
                    return [$officeId => ['name' => $officeName, 'count' => $count]];
                })->toArray();
            })->toArray()
        ];

        $uploadedCount = Content::where('status', 1)->where('created_by', Auth::id())->count();
        $favCount = UserContentActivity::where('activity_type', 1)->where('user_id', Auth::id())->count();
        $savedCount = UserContentActivity::where('activity_type', 2)->where('user_id', Auth::id())->count();
        $employeesCount = User::where('user_type', 3)->where('status', 1)->count();
        $usersCount = User::where('user_type', 4)->where('status', 1)->count();

        return view('backend.index', compact('employeesCount', 'categorys', 'contents', 'usersCount', 'favCount', 'savedCount', 'uploadedCount', 'users', 'offices', 'chartData', 'contentCount'));
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