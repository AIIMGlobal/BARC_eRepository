<table class="table table-bordered table-striped align-middle mb-0" id="printExcel">
    <thead>
        <tr style="border: none; display: none;" class="reportLogo">
            <th colspan="8" style="border: none;"><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
        </tr>

        <tr class="text-center" style="border: none;">
            <th colspan="10" style="border: none;" class="py-4">
                <h1>EMPLOYEE TASK REPORT</h1>
            </th>
        </tr>

        <tr style="background: #090979; color: #fff;">
            <th class="text-center" style="width: 10%;">#</th>
            <th>Name</th>
            <th>Employee Category</th>
            <th class="text-center">Date</th>
            <th>Task Type</th>
            <th>Time Area</th>
            <th class="text-center">Total Enagaged Hour</th>
            <th>Authority Name</th>
            <th class="text-center status">Status</th>
            <th class="text-center action">Action</th>
        </tr>
    </thead>

    <tbody>
        @if ($activitys->count() > 0)
            @php
                $i = 1;
            @endphp

            @foreach ($activitys as $activity)
                <tr>
                    <td class="text-center">{{ $i }}</td>

                    <td>{{ $activity->emp->name_en ?? '-' }}</td>
                    <td>{{ $activity->emp->categoryInfo->name ?? '-' }}</td>

                    <td class="text-center">{{ date('d M, Y', strtotime($activity->entry_date)) }}</td>

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

                    <td class="text-center">
                        @foreach (hours() as $index => $hour)
                            @if ($index == $activity->total_hour)
                                {{ $hour }}
                            @endif
                        @endforeach
                    </td>

                    <td>{{ $activity->client->name_en ?? '-' }}</td>
                    
                    <td class="text-center status">
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

                    <td class="text-center action">
                        @can('view_activity')
                            <a href="{{ route('admin.activity.show', Crypt::encryptString($activity->id)) }}" title="View" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                            </a>
                        @endcan

                        @can('edit_activity')
                            <a href="{{ route('admin.activity.edit', Crypt::encryptString($activity->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                            </a>
                        @endcan

                        @can('delete_activity')
                            <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.activity.delete', Crypt::encryptString($activity->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                <i class="las la-times-circle" style="font-size: 1.6em;"></i>
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