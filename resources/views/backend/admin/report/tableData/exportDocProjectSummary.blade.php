<img height="50" width="150" src="{{$image}}" alt="">
<h4 style="text-align: center;">PROJECT SUMMARY REPORT</h4>

<style>
    table{
        border-collapse: collapse; 
    }
</style>

<table>
    <thead>
        <tr>
            <th style="background-color: #090979;color:#f3f3f3;width:70px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">No.</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Name</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Project Category</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Project Manager</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px;">Client Name</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Project Value</th>
            <th style="background-color: #090979;color:#f3f3f3;width:100px;border:1px solid #f3f3f3; padding: 5px 10px; text-align: center;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($projects->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($projects as $project)
                <tr>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $i }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $project->name ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $project->category->name ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $project->pm->name_en ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px;">{{ $project->client->name_en ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">{{ $project->amount }}</td>
                    
                    <td style="border: 1px solid #000; padding: 5px 10px; text-align: center;">
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

