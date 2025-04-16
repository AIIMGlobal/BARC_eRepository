<?php

namespace App\Exports;

use App\Models\Leave;
use App\Models\LeaveCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

// use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\File;

class LeaveData implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Leave::all();
    // }

    // protected $data;

    function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        $query = Leave::latest();

        $data = $this->data;

        if ($data) {

            if (isset($data['employee_id']) and $data['employee_id'] != '') {
                $query->whereHas('userInfo', function($query2) use ($data) {
                    $query2->where('employee_id', $data['employee_id']);
                });
            }

            if (isset($data['category_id']) && $data['category_id'] != '') {
                $query->where('leave_category_id', $data['category_id']);
            }

            if (isset($data['user_id']) && $data['user_id'] != '') {
                $query->where('user_id', $data['user_id']);
            }

            if ($data['from_date'] && $data['from_date'] != '') {
                $query->whereDate('from_date', '>=', $data['from_date']);
            }
            
            if ($data['to_date'] && $data['to_date'] != '') {
                $query->whereDate('to_date', '<=', $data['to_date']);
            }
        }

        $leaves = $query->get();

        $global_setting = \App\Models\Setting::oldest()->first();

        if (( $global_setting->logo ?? '' )) {
            $image = public_path('/storage/logo/'.( $global_setting->logo ?? '' ).'');
            if (File::exists($image)) {

            } else {
                $image = '';
            }
        } else {
            $image = '';
        }

        $totalLeaves = LeaveCategory::where('status', 1)->sum('day_number');

        return view('backend.admin.report.tableData.xlExportLeave', [
            'leaves' => $leaves,
            'image' => $image,
            'totalLeaves' => $totalLeaves,
        ]);
    }
}
