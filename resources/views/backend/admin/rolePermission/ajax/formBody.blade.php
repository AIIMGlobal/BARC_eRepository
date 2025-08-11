<div class="py-4">
    <div class="row">
        <!-- Assigned Permissions Section -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">{{ __('pages.Given Permissions') }}</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="searchAssigned" class="form-control search-input" placeholder="Search permissions...">
                        <button class="btn btn-sm btn-light me-2" id="selectAllAssigned">{{ __('pages.Select All') }}</button>
                        <button class="btn btn-sm btn-outline-light" id="removeAllAssigned">{{ __('pages.Remove All') }}</button>
                    </div>
                </div>

                <form action="{{route('admin.rolePermission.removePermission')}}" method="POST">
                    @csrf

                    @foreach ($rolePermissions as $permission)
                        <input type="hidden" name="hiddenRoleId" value="{{ $permission->role_id }}">
                    @endforeach

                    <div class="card-body" style="height: 400px; overflow-y: auto;">
                        <div class="row g-3" id="assignedPermissionList">
                            @php $i = 1; @endphp

                            @foreach ($rolePermissions as $permission)
                                <div class="col-12 col-md-6 col-xl-4 permission-item" data-name="{{ strtolower(\Str::title(str_replace('_', ' ', $permission->permissionName->name_en ?? '-'))) }}">
                                    <div class="form-check p-3 alert-success rounded border d-flex align-items-center">
                                        <input class="form-check-input me-2 assignedPermissions" type="checkbox" name="removePermission[]" id="removePermission{{ $i }}" value="{{ $permission->permission_id }}" style="margin-top: 0;">
                                        <label class="form-check-label" for="removePermission{{ $i }}" style="word-break: break-word; color: #000;">
                                            {{ $permission->permissionName ? \Str::title(str_replace('_', ' ', $permission->permissionName->name_en)) : '-' }}
                                        </label>
                                    </div>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        @can('remove_assign_permission')
                            <button type="submit" class="btn btn-danger w-100">{{ __('pages.Remove Permission') }}</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>

        <!-- Unassigned Permissions Section -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">{{ __('pages.Non-Given Permissions') }}</h5>

                    <div class="d-flex align-items-center">
                        <input type="text" id="searchUnassigned" class="form-control search-input" placeholder="Search permissions...">

                        <button class="btn btn-sm btn-light me-2" id="selectAllUnassigned">{{ __('pages.Select All') }}</button>
                        <button class="btn btn-sm btn-outline-light" id="removeAllUnassigned">{{ __('pages.Remove All') }}</button>
                    </div>
                </div>

                <form action="{{ route('admin.rolePermission.givePermission') }}" method="POST">
                    @csrf

                    <input type="hidden" name="hiddenRoleId" value="{{ $selected_role_id }}">

                    <div class="card-body" style="height: 400px; overflow-y: auto;">
                        <div class="row g-3" id="unassignedPermissionList">
                            @php $i = 1; @endphp

                            @foreach ($unassignedPermissions as $up)
                                <div class="col-12 col-md-6 col-xl-4 permission-item" data-name="{{ strtolower(\Str::title(str_replace('_', ' ', $up->name_en ?? '-'))) }}">
                                    <div class="form-check p-3 alert-danger rounded border d-flex align-items-center">
                                        <input class="form-check-input me-2 unassignedPermissions" type="checkbox" name="givePermission[]" id="givePermission{{ $i }}" value="{{ $up->id }}" style="margin-top: 0;">

                                        <label class="form-check-label" for="givePermission{{ $i }}" style="word-break: break-word; color: #000;">
                                            {{ $up->name_en ? \Str::title(str_replace('_', ' ', $up->name_en)) : '-' }}
                                        </label>
                                    </div>
                                </div>

                                @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        @can('assign_permission')
                            <button type="submit" class="btn btn-success w-100">{{ __('pages.Give Permission') }}</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>