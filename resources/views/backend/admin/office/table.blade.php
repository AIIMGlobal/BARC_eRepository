@if ($offices->count() > 0)
    @php
        $i = 1;
    @endphp

    @foreach ($offices as $office)
        <tr>
            <td class="text-center">{{ $i }}</td>

            <td class="text-center">
                @if ($office->logo)
                    <img src="{{ asset('storage/' . $office->logo) }}" alt="Logo" style="max-height: 50px;">
                @else
                    <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Logo" style="max-height: 50px;">
                @endif
            </td>

            <td>{{ $office->name ?? '-' }}</td>
            <td>{{ $office->division->name_en ?? '-' }}</td>

            {{-- <td>{{ $office->district->name_en ?? '-' }}</td> --}}
            {{-- <td>{{ $office->upazila->name_en ?? '-' }}</td> --}}
            
            <td class="text-center">
                @if ($office->status == 0)
                    <span class="badge bg-danger">Inactive</span>
                @elseif($office->status == 1)
                    <span class="badge bg-success">Active</span>
                @endif
            </td>

            <td class="text-center">
                @can('view_office')
                    <a href="{{ route('admin.office.show', Crypt::encryptString($office->id)) }}" title="Details" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                        <i class="las la-eye" style="font-size: 1.6em;"></i>
                    </a>
                @endcan

                @can('edit_office')
                    <a href="{{ route('admin.office.edit', Crypt::encryptString($office->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                        <i class="las la-edit" style="font-size: 1.6em;"></i>
                    </a>
                @endcan

                @can('delete_office')
                    <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-icon waves-effect waves-light destroy" data-id="{{ Crypt::encryptString($office->id) }}" title="Delete">
                        <i class="las la-trash" style="font-size: 1.6em;"></i>
                    </a>
                @endcan
            </td>
        </tr>

        @php
            $i++;
        @endphp
    @endforeach
@else
    {{-- <tr>
        <td colspan="100%" class="text-center"><b>{{__('pages.No Data Found') }}</b></td>
    </tr> --}}
@endif