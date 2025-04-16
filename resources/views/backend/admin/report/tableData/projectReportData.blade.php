<table class="table table-bordered table-striped align-middle mb-0" id="printExcel">
    <thead>
        <tr style="border: none; display: none;" class="reportLogo">
            <th colspan="8" style="border: none;"><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
        </tr>

        <tr class="text-center" style="border: none;">
            <th colspan="8" style="border: none;" class="py-4">
                <h1>PROJECT SUMMARY REPORT</h1>
            </th>
        </tr>

        <tr style="background: #090979; color: #fff;">
            <th class="text-center" style="width: 10%;">No.</th>
            <th>Name</th>
            <th>Project Category</th>
            <th>Project Manager</th>
            <th>Client Name</th>
            <th>Project Value</th>
            <th class="text-center status">Status</th>
            <th class="text-center action">Action</th>
        </tr>
    </thead>

    <tbody>
        @if ($projects->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($projects as $project)
                <tr>
                    <td class="text-center">{{ $i }}</td>
                    <td>{{ $project->name ?? '-' }}</td>
                    <td>{{ $project->category->name ?? '-' }}</td>
                    <td>{{ $project->pm->name_en ?? '-' }}</td>
                    <td>{{ $project->client->name_en ?? '-' }}</td>
                    <td>{{ $project->amount }}</td>
                    
                    <td class="text-center status">
                        @if ($project->status == 3)
                            <span class="badge bg-success">Completed</span>
                        @elseif($project->status == 1)
                            <span class="badge bg-info">On Going</span>
                        @elseif($project->status == 2)
                            <span class="badge bg-danger">Canceled</span>
                        @else
                            <span class="badge bg-primary">Pending</span>
                        @endif
                    </td>

                    <td class="text-center action">
                        @can('view_pl_report')
                            <a href="{{ route('admin.report.showPL', Crypt::encryptString($project->id)) }}" title="View P AND L Report" type="button" class="btn btn-success waves-effect waves-light">
                                View P&L Report
                            </a>
                        @endcan
                    </td>
                </tr>

                @php
                    $i++;
                @endphp
            @endforeach
        @else
            <tr>
                <td colspan="100%" class="text-center"><b>No Data Found</b></td>
            </tr>
        @endif
    </tbody>

    <tfoot class="tfoot">
        <tr style="border: none;">
            <td colspan="8" style="border: none;">
                <p style="font-size: 13px;">
                    <b style="font-size: 16px;">ServicEngine Ltd.</b>
                    <br>
                    <b>Branch Office:</b> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100
                    <br>
                    <b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) 
                    <br> 
                    Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176
                    <br>
                    sebpo.com
                </p>
            </td>
        </tr>
    </tfoot>
    <!-- end tbody -->
</table>
<!-- end table -->