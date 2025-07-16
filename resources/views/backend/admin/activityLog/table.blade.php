@if ($logs->count() > 0)
    @php
        $i = 1;
    @endphp

    @foreach ($logs as $log)
        <tr>
            <td class="text-center">{{ $i }}</td>
            <td>{{ $activityTypes[$log->type] ?? $log->type }}</td>
            <td>{{ $log->user->name_en ?? 'Unknown' }}</td>
            <td>{{ $log->content_name ?? '-' }}</td>
            <td>{{ $log->description ?? '-' }}</td>
            <td>{{ $log->ip_address ?? '-' }}</td>
            <td>{{ $log->created_at->format('d M, Y H:i a') }}</td>
        </tr>

        @php
            $i++;
        @endphp
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center">No data found</td>
    </tr>
@endif