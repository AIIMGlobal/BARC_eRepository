@if ($categorys->count() > 0)
    @php
        $i = 1;
    @endphp

    @foreach ($categorys as $category)
        <tr>
            <td class="text-center">{{ $i }}</td>

            <td>{{ $category->category_name ?? '-' }}</td>
            <td>{{ $category->parent->category_name ?? 'Parent Category' }}</td>
            
            <td class="text-center">
                @if ($category->status == 0)
                    <span class="badge bg-danger">Inactive</span>
                @elseif($category->status == 1)
                    <span class="badge bg-success">Active</span>
                @endif
            </td>

            <td class="text-center">
                @can('view_category')
                    <a href="{{ route('admin.category.show', Crypt::encryptString($category->id)) }}" title="Details" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                        <i class="las la-eye" style="font-size: 1.6em;"></i>
                    </a>
                @endcan

                @can('edit_category')
                    <a href="{{ route('admin.category.edit', Crypt::encryptString($category->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                        <i class="las la-edit" style="font-size: 1.6em;"></i>
                    </a>
                @endcan

                @can('delete_category')
                    <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-icon waves-effect waves-light destroy" data-id="{{ Crypt::encryptString($category->id) }}" title="Delete">
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