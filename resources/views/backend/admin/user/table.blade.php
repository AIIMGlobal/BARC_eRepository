@if ($users->count() > 0)
    @php
        $i = 1;
    @endphp

    @foreach ($users as $user)
        <tr data-id="{{ $user->id }}">
            <td class="text-center">{{ $i }}</td>

            <td>
                {{ $user->name_en ?? '-' }}
            </td>

            <td>{{ $user->email ?? '-' }}</td>

            <td class="text-center">{{ $user->mobile ?? '-' }}</td>

            <td class="text-center">
                @if ($user->user_type == 2)
                    Admin
                @elseif ($user->user_type == 3)
                    Employee
                @elseif ($user->user_type == 4)
                    User
                @endif
            </td>

            <td>{{ $user->role->display_name ?? '-' }}</td>

            @if ($user->user_type == 4)
                <td>{{ $user->userInfo->designation ?? '-' }}</td>
            @else
                <td>{{ $user->userInfo->post->name ?? '-' }}</td>
            @endif

            <td class="text-center">
                @if ($user->status == 1)
                    <span class="badge bg-success">Approved</span>
                @elseif ($user->status == 0)
                    <span class="badge bg-primary">Pending</span>
                @elseif ($user->status == 2)
                    <span class="badge bg-danger">Declined</span>
                @elseif ($user->status == 4)
                    <span class="badge bg-info">Pending Email Verification</span>
                @endif
            </td>

            <td class="text-center">
                @can('view_user')
                    <a href="{{ route('admin.user.show', Crypt::encryptString($user->id)) }}" title="Show" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
                        <i class="las la-eye" style="font-size: 1.5em;"></i>
                    </a>
                @endcan
                
                @can('edit_user')
                    <a href="{{ route('admin.user.edit', Crypt::encryptString($user->id)) }}" title="Edit" class="btn btn-sm btn-primary btn-icon waves-effect waves-light">
                        <i class="las la-edit" style="font-size: 1.5em;"></i>
                    </a>
                @endcan

                @can('block_user')
                    @if ($user->status == 1)
                        <a href="javascript:void(0)" onclick="archive({{ $user->id }})" title="Archive" class="btn btn-sm btn-warning btn-icon waves-effect waves-light">
                            <i class="las la-lock" style="font-size: 1.5em;"></i>
                        </a>
                    @endif

                    @if ($user->status != 5)
                        <a href="javascript:void(0)" onclick="destroy({{ $user->id }})" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                            <i class="las la-trash" style="font-size: 1.5em;"></i>
                        </a>
                    @endif
                @endcan
            </td>
        </tr>

        @php
            $i++;
        @endphp
    @endforeach
@else
    {{-- <tr>
        <td colspan="8" class="text-center"><b>No Data Found</b></td>
    </tr> --}}
@endif