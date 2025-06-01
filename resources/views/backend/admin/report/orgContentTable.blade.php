@php
    $i = 1;
@endphp

@forelse($reports as $report)
    <tr>
        <td class="text-center">{{ $i }}</td>

        <td>{{ $report->createdBy->userInfo->office->name ?? 'N/A' }}</td>
        <td>{{ $report->content_name ?? 'N/A' }}</td>
        <td>{{ $report->category->category_name ?? 'N/A' }}</td>
        <td>{{ $report->content_type ?? 'N/A' }}</td>
        <td>{{ $report->createdBy->name_en ?? 'N/A' }}</td>

        {{-- <td class="text-center">1</td> --}}

        <td class="actionBtn text-center">
            @can('view_content')
                <a href="{{ route('admin.content.show', Crypt::encryptString($report->id)) }}" title="Show" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
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