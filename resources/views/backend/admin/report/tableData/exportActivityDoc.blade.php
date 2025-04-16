<img height="50" width="150" src="{{ $image }}" alt="">

<h4 style="text-align: center;">ACTIVITY REPORT</h4>

<style>
    table{
        border-collapse: collapse; 
    }
</style>

<table>
    <thead>
        <tr>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3; text-align: center;">No.</th>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3;">Name</th>
            <th style="background-color: #090979; color:#f3f3f3; width:70px; border:1px solid #f3f3f3;">Employee Category</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3; text-align: center;">Date</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Task Type</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Time Area</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3; text-align: center;">Total Enagaged Hour</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3;">Authority Name</th>
            <th style="background-color: #090979; color:#f3f3f3; width:100px; border:1px solid #f3f3f3; text-align: center;">Status</th>
        </tr>
    </thead>

    <tbody>
        @if ($activitys->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($activitys as $activity)
                <tr>
                    <td style="text-align: center">{{ $i }}</td>

                    <td>{{ $activity->emp->name_en ?? '-' }}</td>
                    <td>{{ $activity->emp->categoryInfo->name ?? '-' }}</td>

                    <td style="text-align: center">{{ date('d M, Y', strtotime($activity->entry_date)) }}</td>

                    <td>
                        @foreach (taskTypes() as $index => $taskType)
                            @if ($index == $activity->activity_type)
                                {{ $taskType }}
                            @endif
                        @endforeach
                    </td>

                    <td>
                        @foreach (timeAreas() as $index => $timeArea)
                            @if ($index == $activity->time_area)
                                {{ $timeArea }}
                            @endif
                        @endforeach
                    </td>

                    <td style="text-align: center">
                        @foreach (hours() as $index => $hour)
                            @if ($index == $activity->total_hour)
                                {{ $hour }}
                            @endif
                        @endforeach
                    </td>

                    <td>{{ $activity->client->name_en ?? '-' }}</td>
                    
                    <td style="text-align: center">
                        @if ($activity->status == 0)
                            <span class="badge bg-danger">Incomplete</span>
                        @elseif($activity->status == 1)
                            <span class="badge bg-primary">Begin</span>
                        @elseif($activity->status == 2)
                            <span class="badge bg-info">Continous</span>
                        @elseif($activity->status == 3)
                            <span class="badge bg-success">Completed</span>
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