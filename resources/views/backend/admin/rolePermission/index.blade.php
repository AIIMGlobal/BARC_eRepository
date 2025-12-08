@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Give Permission')

@section('content')
    @push('css')
        <style>
            label {
                display: block;
            }
            
            .card-header {
                border-bottom: 0;
            }
            .form-check {
                transition: background-color 0.2s;
                min-height: 80px;
            }
            .form-check:hover {
                background-color: #f1f1f1 !important;
            }
            .form-check-label {
                font-size: 0.95rem;
                cursor: pointer;
                flex-grow: 1;
            }
            .search-input {
                width: 200px;
                margin-right: 10px;
            }

            @media only screen and (max-width: 480px) and (-webkit-min-device-pixel-ratio: 1.5), (max-width: 480px) and (min-resolution: 144dpi) {
                .card-header.bg-success.text-white.d-flex.justify-content-between.align-items-center {
                    display: block !important;
                }
                .search-input {
                    width: 100%;
                }
                button#selectAllAssigned {
                    width: 100%;
                }
                button#removeAllAssigned {
                    width: 100%;
                }

                .card-header.bg-danger.text-white.d-flex.justify-content-between.align-items-center {
                    display: block !important;
                }
                button#selectAllUnassigned {
                    width: 100%;
                }
                button#removeAllUnassigned {
                    width: 100%;
                }
            }
        </style>
    @endpush

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Give Permission</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Give Permission</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="role">Select Role: </label>
                                    <select class="form-control" name="roleId" id="role">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{ $role->id == $selected_role_id ? 'selected' : '' }}>{{$role->display_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-10">
                                    @include('backend.admin.rolePermission.ajax.formBody')
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>

    @push('script')
        <script>
            $(document).ready(function(){
                $("#role").change(function() {
                    let roleId = this.value;
                    showPermissions(roleId);
                });

                function showPermissions(roleId) {
                    let url = "{{ route('admin.rolePermission.showPermission', ':roleId') }}";
                    url = url.replace(':roleId', roleId);

                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            // Clear existing permissions
                            $("#assignedPermissionList").empty();
                            $("#unassignedPermissionList").empty();

                            // Format permission name: Remove underscores and capitalize words
                            function formatPermissionName(name) {
                                if (!name || name === '-') return '-';
                                return name.replace(/_/g, ' ')
                                    .split(' ')
                                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                                    .join(' ');
                            }

                            // Populate Assigned Permissions
                            let assignedIndex = 1;

                            $.each(response.rolePermissions, function (key, item) {
                                let name = formatPermissionName(item.permission_name ? item.permission_name.name_en : '-');
                                $("#assignedPermissionList").append(`
                                    <div class="col-12 col-md-6 col-xl-4 permission-item" data-name="${name.toLowerCase()}">
                                        <div class="form-check p-3 alert-success rounded border d-flex align-items-center">
                                            <input class="form-check-input me-2 assignedPermissions" type="checkbox" name="removePermission[]" id="removePermission${assignedIndex}" value="${item.permission_id}" style="margin-top: 0; flex-shrink: 0;">

                                            <label class="form-check-label" for="removePermission${assignedIndex}" style="word-break: break-word; color: #000;">
                                                ${name}
                                            </label>
                                        </div>
                                    </div>
                                `);
                                assignedIndex++;
                            });

                            // Populate Unassigned Permissions
                            let unassignedIndex = 1;
                            $.each(response.unassignedPermissions, function (key, item) {
                                let name = formatPermissionName(item.name_en || '-');

                                $("#unassignedPermissionList").append(`
                                    <div class="col-12 col-md-6 col-xl-4 permission-item" data-name="${name.toLowerCase()}">
                                        <div class="form-check p-3 alert-danger rounded border d-flex align-items-center">
                                            <input class="form-check-input me-2 unassignedPermissions" type="checkbox" name="givePermission[]" id="givePermission${unassignedIndex}" value="${item.id}" style="margin-top: 0; flex-shrink: 0;">

                                            <label class="form-check-label" for="givePermission${unassignedIndex}" style="word-break: break-word; color: #000;">
                                                ${name}
                                            </label>
                                        </div>
                                    </div>
                                `);
                                unassignedIndex++;
                            });

                            // Update hidden role ID in both forms to match the selected role
                            $('input[name="hiddenRoleId"]').val(roleId);

                            // Trigger search filter on load
                            filterPermissions('#searchAssigned', '#assignedPermissionList');
                            filterPermissions('#searchUnassigned', '#unassignedPermissionList');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching permissions:', error);
                        }
                    });
                }

                // Search filter function
                function filterPermissions(searchInput, permissionList) {
                    $(searchInput).on('input', function() {
                        let searchTerm = $(this).val().toLowerCase();
                        $(permissionList).find('.permission-item').each(function() {
                            let permissionName = $(this).data('name');
                            if (permissionName.includes(searchTerm)) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    });
                }

                // Initialize search filters
                filterPermissions('#searchAssigned', '#assignedPermissionList');
                filterPermissions('#searchUnassigned', '#unassignedPermissionList');

                // Select/Remove All Assigned Permissions
                $("#selectAllAssigned").click(function(){
                    $(".assignedPermissions:visible").prop('checked', true);
                });
                $("#removeAllAssigned").click(function(){
                    $(".assignedPermissions:visible").prop('checked', false);
                });

                // Select/Remove All Unassigned Permissions
                $("#selectAllUnassigned").click(function(){
                    $(".unassignedPermissions:visible").prop('checked', true);
                });
                $("#removeAllUnassigned").click(function(){
                    $(".unassignedPermissions:visible").prop('checked', false);
                });
            });
        </script>
    @endpush
@endsection