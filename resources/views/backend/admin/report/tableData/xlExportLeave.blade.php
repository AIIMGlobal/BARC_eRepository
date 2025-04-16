<table>
    <thead>
        <tr>
            <th colspan="7">
                @if ($image)
                    <img width='100px' src="{{ $image }}" alt="">
                @endif
            </th>
        </tr>

        <tr>
            <th colspan="7" style="text-align: center; font-size: 20px; font-weight: bolder;">
                <h1>LEAVE SUMMARY REPORT</h1>
            </th>
        </tr>

        <tr>
            <th colspan="7"> </th>
        </tr>

        <tr>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3;">No.</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Employee Name</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Leave Category</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Total Days</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">From Date</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">To Date</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Total Taken Leave</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Total Remaining Leave</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($leaves->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($leaves as $leave)
            @php
                    $totalTakenLeaves = App\Models\Leave::where('user_id', $leave->user_id)->where('status', 1)->sum('day_count');;
                    $totalTaken = 0;
                    $totalRemainingLeaves = 0;
                    $totalRemain = 0;

                    $takenLeave = App\Models\Leave::where('id', $leave->id)->where('user_id', $leave->user_id)->where('status', 1)->first();
                    $totalTaken += $takenLeave->day_count ?? 0;
                    $totalRemain = ($leave->category->day_number ?? 0) - ($takenLeave->day_count ?? 0);

                    $totalRemainingLeaves = $totalLeaves - $totalTakenLeaves ?? 0;
                @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $leave->employee->name_en ?? '-' }}</td>
                    <td>{{ $leave->category->name_en ?? '-' }}</td>
                    <td>{{ $leave->day_count ?? '-' }}</td>
                    <td>{{ date('d M, Y', strtotime($leave->from_date)) }}</td>
                    <td>{{ date('d M, Y', strtotime($leave->to_date)) }}</td>
                    <td>{{ $totalTaken->day_count ?? 0 }}</td>
                    <td>{{ $totalRemain ?? 0 }}</td>
                    
                    <td>
                        @if ($leave->status == 3)
                            <span>Completed</span>
                        @elseif($leave->status == 1)
                            <span>On Going</span>
                        @elseif($leave->status == 2)
                            <span>Canceled</span>
                        @else
                            <span>Pending</span>
                        @endif
                    </td>
                </tr>

                @php
                    $i++;
                @endphp
            @endforeach
        @else
            <tr>
                <td colspan="9" style="text-align:center"><b>No Data Found</b></td>
            </tr>
        @endif
        <tr>
            <th colspan="6" style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;text-align:right;">Total</th>
            <td style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;text-align:center;">{{ $totalTakenLeaves }}</td>
            <td style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;text-align:center;" >{{ $totalRemainingLeaves }}</td>
            <td style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;text-align:center;" ></td>
        </tr>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="7"> </td>
        </tr>

        <tr>
            <td colspan="7">
                <p><strong>ServicEngine Ltd.</strong></p>
            </td>
        </tr>

        <tr>
            <td colspan="7">
                <p><strong><b>Branch Office:</b></strong> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100</p>
            </td>
        </tr>

        <tr>
            <td colspan="7">
                <b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) 
            </td>
        </tr>

        <tr>
            <td colspan="7">
                Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176
            </td>
        </tr>

        <tr>
            <td colspan="7">
                sebpo.com
            </td>
        </tr>
    </tfoot>
    
    <!-- end tbody -->
</table>
<!-- end table -->