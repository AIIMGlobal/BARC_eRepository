@php
    $i = 1;
@endphp

@forelse($reports as $report)
    <tr>
        <td class="text-center">{{ $i }}</td>
        <td>{{ $report->userInfo->office->name ?? 'N/A' }}</td>
        <td>{{ $report->name_en }}</td>
        <td>{{ $report->email }}</td>
        <td>{{ $report->mobile ?? 'N/A' }}</td>
        <td>{{ $report->userInfo->post->name ?? 'N/A' }}</td>
        <td>{{ $report->categoryInfo->name ?? 'N/A' }}</td>

        <td class="actionBtn text-center">
            @can('view_user')
                <a href="{{ route('admin.user.show', Crypt::encryptString($report->id)) }}" title="Show" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
                    <i class="las la-eye" style="font-size: 1.5em;"></i>
                </a>
            @endcan
        </td>
    </tr>

    @php
        $i++;
    @endphp
@empty
    <tr>
        <td colspan="7" class="text-center">No data available</td>
    </tr>
@endforelse