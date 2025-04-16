<?php

namespace App\Exports;

use App\Models\TenderHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Project;
use App\Models\ProjectValue;
use App\Models\ProjectTransaction;
// use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\File;

class PlReport implements FromView
{
    function __construct($id) {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;

        $project = Project::where('id', $id)->first();
        $project_value = ProjectValue::where('project_id', $id)->first();
        $projectEarnings = ProjectTransaction::where('project_id', $id)->where('type', 1)->get();
        $projectExpenses = ProjectTransaction::where('project_id', $id)->where('type', 2)->get();

        $global_setting = \App\Models\Setting::oldest()->first();

        $image = public_path('/storage/logo/'.( $global_setting->logo ?? '' ).'');

        return view('backend.admin.report.tableData.xlExportPL', [
            'project' => $project,
            'image' => $image,
            'project_value' => $project_value,
            'projectEarnings' => $projectEarnings,
            'projectExpenses' => $projectExpenses,
        ]);
    }
}
