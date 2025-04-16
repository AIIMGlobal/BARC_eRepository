@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Give Permission')
@section('content')
    @push('css')
        <style>
            label {
                display: block;
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
                                        @if ($selected_role != '')
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" @if($selected_role->id == $role->id) selected @endif>{{$role->display_name}}</option>
                                            @endforeach
                                        @else
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}">{{$role->display_name}}</option>
                                            @endforeach
                                        @endif
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

                function showPermissions(roleId){
                    let url = "{{ route('admin.rolePermission.showPermission', ':roleId') }}";
                    url = url.replace(':roleId', roleId);

                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            $("#assignedPermissionList").empty();
                            $.each(response.rolePermissions, function (key, item) {
                                if (item.permission_name) {
                                    var name = item.permission_name.name_en;
                                } else {
                                    var name = '-';
                                }

                                $("#assignedPermissionList").append('<div class="bg-soft-success m-1 p-2 col-md-2" style="border:1px solid rgba(0, 0, 0, 0.319);">\
                                    <input type="hidden" name="hiddenRoleId" value="'+item.role_id+'">\
                                    <input class="assignedPermissions" type="checkbox" name="removePermission[]" id="removePermission'+item.permission_id+'" value="'+item.permission_id+'">\
                                    <label class="form-check-label" for="removePermission'+item.permission_id+'">'+name+'</label>\
                                </div>');
                            });

                            $("#unassignedPermissionList").empty();

                            $.each(response.unassignedPermissions, function (key, item) {
                                $("#unassignedPermissionList").append('<div class="bg-soft-danger m-1 p-2 col-md-2" style="border:1px solid rgba(0, 0, 0, 0.319);">\
                                    <input type="hidden" name="hiddenRoleId" value="'+roleId+'">\
                                    <input class="unassignedPermissions" type="checkbox" name="givePermission[]" id="givePermission'+item.id+'" value="'+item.id+'">\
                                    <label class="form-check-label" for="givePermission'+item.id+'">'+item.name_en+'</label>\
                                </div> ');
                            });
                        }
                    });
                }
            });
        </script>

        <script>
            // select, remove all assigned permissions
            $("#selectAllAssigned").click(function(){
                $(".assignedPermissions").prop('checked', true);
            });
            $("#removeAllAssigned").click(function(){
                $(".assignedPermissions").prop('checked', false);
            });

            // select, remove all unassigned permissions
            $("#selectAllUnassigned").click(function(){
                $(".unassignedPermissions").prop('checked', true);
            });
            $("#removeAllUnassigned").click(function(){
                $(".unassignedPermissions").prop('checked', false);
            });
        </script>
    @endpush
@endsection
