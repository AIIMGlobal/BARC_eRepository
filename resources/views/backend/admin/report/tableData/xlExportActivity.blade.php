<table>
    <thead>
        <tr>
            <th colspan="7">
                <img width='100px' src="{{ $image }}" alt="">
            </th>
        </tr>

        <tr>
            <th colspan="7" style="text-align: center; font-size: 20px; font-weight: bolder;">
                <h1>ACTIVITY REPORT</h1>
            </th>
        </tr>

        <tr>
            <th colspan="7"> </th>
        </tr>

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