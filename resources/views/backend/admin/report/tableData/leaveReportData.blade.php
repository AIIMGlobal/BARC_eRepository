<table class="table table-bordered table-striped align-middle mb-0" id="printExcel">
    <thead>
        <tr style="border: none; display: none;" class="reportLogo">
            <th colspan="8" style="border: none;"><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
        </tr>

        <tr class="text-center" style="border: none;">
            <th colspan="8" style="border: none;" class="py-4">
                <h1>LEAVE REPORT</h1>
            </th>
        </tr>

        <tr style="background: #090979; color: #fff;">
            <th class="text-center" style="width: 10%;">No.</th>
            <th>Employee Name</th>
            <th>Leave Category</th>
            <th class="text-center">Total Days</th>
            <th class="text-center">From Date</th>
            <th class="text-center">To Date</th>
            <th class="text-center">Status</th>
            <th class="text-center">Total Taken Leave</th>
            <th class="text-center">Total Remaining Leave</th>
        </tr>
    </thead>

    <tbody>
        @if ($leaves->count() > 0)
            @php
                $i = 1;

                $totalTakenLeaves = 0;
                $totalRemainingLeaves = 0;
                $user_leave_counts_array = array();
                $user_leave_remain_array = array();
            @endphp

            @foreach ($leaves as $leave)
                @php
                    if ($leave->status == 1) {
                        $user_leave_counts_array[$leave->user_id] = (($leave->day_count ?? 0) + ($user_leave_counts_array[$leave->user_id] ?? 0));

                        $user_leave_remain_array[$leave->user_id] = ($totalLeaves - ($user_leave_counts_array[$leave->user_id]));

                        $totalTakenLeaves = $user_leave_counts_array[$leave->user_id] ?? 0;
                        $totalRemainingLeaves = $user_leave_remain_array[$leave->user_id] ?? 0;
                    } else {
                        $user_leave_remain_array[$leave->user_id] = ($totalLeaves - (($user_leave_counts_array[$leave->user_id] ?? 0)));
                    }
                @endphp

                <tr>
                    <td class="text-center">{{ $i }}</td>
                    
                    <td>
                        {{ $leave->employee->name_en ?? '-' }}
                        <br>
                        (EID: {{ $leave->employee->userInfo->employee_id ?? '-' }})
                    </td>

                    <td>{{ $leave->category->name_en ?? '-' }}</td>
                    <td class="text-center">{{ $leave->day_count ?? '-' }}</td>
                    <td class="text-center">{{ date('d M, Y', strtotime($leave->from_date)) }}</td>
                    <td class="text-center">{{ date('d M, Y', strtotime($leave->to_date)) }}</td>

                    <td class="text-center">
                        @if ($leave->status == 2)
                            <span class="badge bg-danger">Declined</span>
                        @elseif($leave->status == 1)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-primary">Pending</span>
                        @endif
                    </td>
                    
                    <td class="text-center">
                        {{ ($user_leave_counts_array[$leave->user_id] ?? 0) }}
                    </td>

                    <td class="text-center">
                        {{ ($user_leave_remain_array[$leave->user_id] ?? 0) }}
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

        @if ((isset($_GET['user_id']) and $_GET['user_id'] != ''))
            <tr>
                <th colspan="7" style="text-align: right;">Total</th>
                <td class="text-center">{{ $totalTakenLeaves ?? 0 }}</td>
                <td class="text-center" colspan="2">{{ $totalRemainingLeaves ?? 0 }}</td>
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