<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

// use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportData implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Project::all();
    // }

    // protected $data;

    function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        $query = Project::latest();

        $data = $this->data;

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


        return view('backend.admin.report.tableData.xlExport', [
            'projects' => $projects,
            'image' => $image,
        ]);
    }
}
