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
                <h1>TENDER REPORT</h1>
            </th>
        </tr>

        <tr>
            <th colspan="7"> </th>
        </tr>

        <tr>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3;">No.</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Date of Submission</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Tender Type</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Work Title</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Lot</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Name of Authority</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Organiztion Status-Lead Partner (If JV)</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Concerned Person</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($tenders->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($tenders as $tender)
            
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ date('d M, Y', strtotime($tender->date_of_submission)) }}</td>
                    <td>{{ $tender->tenderType->name ?? '-' }}</td>
                    <td>{{ $tender->work_title }}</td>
                    <td>{{ $tender->lot ?? '-' }}</td>
                    <td>{{ $tender->client->name_en ?? '-' }}</td>
                    <td>{{ $tender->organization_jv_name }}</td>
                    <td>{{ $tender->concerned_person ?? '-' }}</td>
                    
                    <td>
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