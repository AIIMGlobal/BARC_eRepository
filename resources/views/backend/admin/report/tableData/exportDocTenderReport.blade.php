<img height="50" width="150" src="{{$image}}" alt="">
<h4 style="text-align: center;">TENDER SUMMARY REPORT</h4>

<style>
    table{
        border-collapse: collapse; 
    }
</style>

<table>
    <thead>
        <tr>
            <th style="background-color: #090979;color:#f3f3f3;width:70px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">No.</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Date of Submission</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Tender Type</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Work Title</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Lot</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Name of Authority</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Organiztion Status-Lead Partner (If JV)</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Concerned Person</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($tenders->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($tenders as $tender)
            
                <tr>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $i }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ date('d M, Y', strtotime($tender->date_of_submission)) }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $tender->tenderType->name ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $tender->work_title }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $tender->lot ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $tender->client->name_en ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $tender->organization_jv_name }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $tender->concerned_person ?? '-' }}</td>
                    
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">
                        @if ($tender->status == 3)
                            <span>Completed</span>
                        @elseif($tender->status == 1)
                            <span>On Going</span>
                        @elseif($tender->status == 2)
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

