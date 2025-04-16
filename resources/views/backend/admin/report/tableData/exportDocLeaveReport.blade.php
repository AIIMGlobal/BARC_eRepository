<img height="50" width="150" src="{{$image}}" alt="">
<h4 style="text-align: center;">LEAVE SUMMARY REPORT</h4>

<style>
    table{
        border-collapse: collapse; 
    }
</style>

<table>
    <thead>
        <tr>
            <th style="background-color: #090979;color:#f3f3f3;width:70px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">No.</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Employee Name</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Leave Category</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Total Days</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">From Date</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">To Date</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Total Taken Leave</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Total Remaining Leave</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Status</th>
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
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $i }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $leave->employee->name_en ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $leave->category->name_en ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $leave->day_count ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ date('d M, Y', strtotime($leave->from_date)) }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ date('d M, Y', strtotime($leave->to_date)) }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $totalTaken->day_count ?? 0 }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $totalRemain ?? 0 }}</td>
                    
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">
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
</table>

<br>
<br>

<div>
    <span><strong>ServicEngine Ltd.</strong></span>
    <br>
    <span><strong><b>Branch Office:</b></strong> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100</span>
    <span><b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) </p>
    <span>Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176</span>
    <span>sebpo.com</span>
</div>

<!-- end table -->

