<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;

/* included models */
use App\Models\ActivityPurpose;
use App\Models\User;
use App\Models\Project;

class AjaxController extends Controller
{
    public function taskPurposesByType(Request $request)
    {
        $data = $request->all();

        $purposes = ActivityPurpose::where('activity_type', $data['activity_type'])->where('status', 1)->select('id', 'name')->get();

        return Response::json($purposes);
    }
    
    public function projectsByClient(Request $request)
    {
        $data = $request->all();

        $projects = Project::where('client_id', $data['client_id'])->orderBy('name')->get();

        return Response::json($projects);
    }
}
