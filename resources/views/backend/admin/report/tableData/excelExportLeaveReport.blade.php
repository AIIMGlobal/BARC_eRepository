<table>
    <thead>
        <tr>
            <th colspan="7">
                <img width='100px' src="{{ $image }}" alt="">
            </th>
        </tr>

        <tr>
            <th colspan="7" style="text-align: center; font-size: 20px; font-weight: bolder;">
                <h1>PROJECT SUMMARY REPORT</h1>
            </th>
        </tr>

        <tr>
            <th colspan="7"> </th>
        </tr>

        <tr>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3;">No.</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Name</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Project Category</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Project Manager</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Client Name</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Project Value</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($projects->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($projects as $project)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $project->name ?? '-' }}</td>
                    <td>{{ $project->category->name ?? '-' }}</td>
                    <td>{{ $project->pm->name_en ?? '-' }}</td>
                    <td>{{ $project->client->name_en ?? '-' }}</td>
                    <td>{{ $project->amount }}</td>
                    
                    <td>
                        @if ($project->status == 3)
                            <span>Completed</span>
                        @elseif($project->status == 1)
                            <span>On Going</span>
                        @elseif($project->status == 2)
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
                <td colspan="7" style="text-align:center"><b>No Data Found</b></td>
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