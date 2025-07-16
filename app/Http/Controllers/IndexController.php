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
        $currentYear = date('Y');
        $officeId = Auth::check() && Auth::user()->role_id == 3 && Auth::user()->userInfo ? Auth::user()->userInfo->office_id : null;
        
        $categorys = Category::where('status', 1)
                            ->withCount(['contents' => function ($query) {
                                $query->where('status', 1);
                            }])
                            ->orderByDesc('contents_count')
                            ->get();

        if (Auth::check() && Auth::user()->role_id == 3 && $officeId) {
            $contents = Content::where('status', 1)
                            ->whereIn('created_by', function ($query) use ($officeId) {
                                $query->select('users.id')
                                      ->from('users')
                                      ->join('user_infos', 'user_infos.user_id', 'users.id')
                                      ->where('users.user_type', 4)
                                      ->where('user_infos.office_id', $officeId);
                            })
                            ->get()
                            ->groupBy(function ($content) {
                                $date = Carbon::parse($content->published_at);
                                return $date->format('Y-m');
                            })
                            ->mapWithKeys(function ($group, $yearMonth) {
                                $date = Carbon::createFromFormat('Y-m', $yearMonth);
                                $label = $date->format('F (Y)');
                                return [$label => $group->count()];
                            })
                            ->sortKeysUsing(function ($a, $b) {
                                $dateA = Carbon::createFromFormat('F (Y)', $a);
                                $dateB = Carbon::createFromFormat('F (Y)', $b);
                                return $dateA <=> $dateB;
                            })
                            ->toArray();
        } elseif (Auth::check() && Auth::user()->user_type == 4) {
            $contents = Content::where('status', 1)
                            ->where('created_by', Auth::id())
                            ->get()
                            ->groupBy(function ($content) {
                                $date = Carbon::parse($content->published_at);
                                return $date->format('Y-m');
                            })
                            ->mapWithKeys(function ($group, $yearMonth) {
                                $date = Carbon::createFromFormat('Y-m', $yearMonth);
                                $label = $date->format('F (Y)');
                                return [$label => $group->count()];
                            })
                            ->sortKeysUsing(function ($a, $b) {
                                $dateA = Carbon::createFromFormat('F (Y)', $a);
                                $dateB = Carbon::createFromFormat('F (Y)', $b);
                                return $dateA <=> $dateB;
                            })
                            ->filter(function ($count) {
                                return $count > 0; // Only include months with content
                            })
                            ->toArray();
        } else {
            $contents = Content::where('status', 1)
                            ->whereYear('published_at', $currentYear)
                            ->get()
                            ->groupBy(function ($content) {
                                $date = Carbon::parse($content->published_at);
                                return $date->format('Y-m');
                            })
                            ->mapWithKeys(function ($group, $yearMonth) {
                                $date = Carbon::createFromFormat('Y-m', $yearMonth);
                                $label = $date->format('F (Y)');
                                return [$label => $group->count()];
                            })
                            ->sortKeysUsing(function ($a, $b) {
                                $dateA = Carbon::createFromFormat('F (Y)', $a);
                                $dateB = Carbon::createFromFormat('F (Y)', $b);
                                return $dateA <=> $dateB;
                            })
                            ->filter(function ($count) {
                                return $count > 0; // Only include months with content
                            })
                            ->toArray();
        }

        $contentCount = Content::where('status', 1)
                            ->when($officeId, function ($query) use ($officeId) {
                                return $query->whereIn('created_by', function ($subQuery) use ($officeId) {
                                    $subQuery->select('users.id')
                                             ->from('users')
                                             ->join('user_infos', 'user_infos.user_id', 'users.id')
                                             ->where('users.user_type', 4)
                                             ->where('user_infos.office_id', $officeId);
                                });
                            })
                            ->count();

        $uploadedCount = Content::where('status', 1)
                                ->where('created_by', Auth::id())
                                ->count();

        $favCount = UserContentActivity::where('activity_type', 1)
                                    ->where('user_id', Auth::id())
                                    ->count();

        $savedCount = UserContentActivity::where('activity_type', 2)
                                        ->where('user_id', Auth::id())
                                        ->count();

        $employeesCount = User::where('user_type', 3)
                            ->where('status', 1)
                            ->when($officeId, function ($query) use ($officeId) {
                                return $query->whereHas('userInfo', function ($q) use ($officeId) {
                                    $q->where('office_id', $officeId);
                                });
                            })
                            ->count();

        $usersCount = User::where('user_type', 4)
                        ->where('status', 1)
                        ->when($officeId, function ($query) use ($officeId) {
                            return $query->whereHas('userInfo', function ($q) use ($officeId) {
                                $q->where('office_id', $officeId);
                            });
                        })
                        ->count();

        $approvedUsers = User::with('userInfo')
                            ->where('user_type', 4)
                            ->where('status', 1)
                            ->when($officeId, function ($query) use ($officeId) {
                                return $query->whereHas('userInfo', function ($q) use ($officeId) {
                                    $q->where('office_id', $officeId);
                                });
                            })
                            ->get();

        $totalApprovedUsers = $approvedUsers->count();

        $officeCounts = $approvedUsers->groupBy('userInfo.office_id')->map->count();

        $officeIds = $approvedUsers->pluck('userInfo.office_id')->unique()->filter();

        $offices = Office::whereIn('id', $officeIds)->get()->mapWithKeys(function ($office) {
            return [$office->id => ['name' => $office->name, 'short_name' => $office->short_name ?? $office->name]];
        })->toArray();

        $chartData = [
            'labels' => $officeCounts->mapWithKeys(function ($count, $officeId) use ($offices) {
                return [$officeId => $offices[$officeId]['name'] ?? 'Unknown Office'];
            })->values()->toArray(),
            'counts' => $officeCounts->values()->toArray(),
            'percentages' => $officeCounts->map(function ($count) use ($totalApprovedUsers) {
                return $totalApprovedUsers > 0 ? round(($count / $totalApprovedUsers) * 100, 2) : 0;
            })->values()->toArray(),
            'short_names' => $officeCounts->mapWithKeys(function ($count, $officeId) use ($offices) {
                return [$officeId => $offices[$officeId]['short_name'] ?? $offices[$officeId]['name'] ?? 'N/A'];
            })->values()->toArray()
        ];

        return view('backend.index', compact('employeesCount', 'categorys', 'contents', 'usersCount', 'favCount', 'savedCount', 'uploadedCount', 'chartData', 'contentCount'));
    }

    public function contentData(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $officeId = Auth::check() && Auth::user()->role_id == 3 && Auth::user()->userInfo ? Auth::user()->userInfo->office_id : null;
        $currentYear = date('Y');

        \Log::info('contentData called', ['month' => $month, 'year' => $year, 'officeId' => $officeId, 'user_id' => Auth::id()]);

        $query = Content::where('status', 1);

        if ($month && $year) {
            $query->whereYear('published_at', $year)->whereMonth('published_at', $month);
        } elseif ($year) {
            $query->whereYear('published_at', $year);
        } elseif (!Auth::check() || Auth::user()->user_type != 4) {
            $query->whereYear('published_at', $currentYear);
        }

        if (Auth::check() && Auth::user()->role_id == 3 && $officeId) {
            $query->whereIn('created_by', function ($subQuery) use ($officeId) {
                $subQuery->select('users.id')
                         ->from('users')
                         ->join('user_infos', 'user_infos.user_id', 'users.id')
                         ->where('users.user_type', 4)
                         ->where('user_infos.office_id', $officeId);
            });
        } elseif (Auth::check() && Auth::user()->user_type == 4) {
            $query->where('created_by', Auth::id());
        }

        $contents = $query->get()
                         ->groupBy(function ($content) {
                             $date = Carbon::parse($content->published_at);
                             return $date->format('Y-m');
                         })
                         ->mapWithKeys(function ($group, $yearMonth) {
                             $date = Carbon::createFromFormat('Y-m', $yearMonth);
                             $label = $date->format('F (Y)');
                             return [$label => $group->count()];
                         })
                         ->sortKeysUsing(function ($a, $b) {
                             $dateA = Carbon::createFromFormat('F (Y)', $a);
                             $dateB = Carbon::createFromFormat('F (Y)', $b);
                             return $dateA <=> $dateB;
                         })
                         ->filter(function ($count) {
                             return $count > 0; // Only include months with content
                         })
                         ->toArray();

        \Log::info('contentData result', ['contents' => $contents]);

        return response()->json($contents);
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