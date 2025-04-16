@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Role List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Role List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Role List</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xxl-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Role List</h4>

                            <div class="flex-shrink-0">
                                @can('add_role')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                        Add New Role
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Role Name</th>
                                            <th class="text-center">Status</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($roles->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($roles as $role)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $role->display_name ?? '-' }}</td>

                                                    <td class="text-center">
                                                        @if ($role->status == 1)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $role->createdUser ? $role->createdUser->full_name : '-' }}</td>

                                                    <td>
                                                        {{ $role->created_at ? $role->created_at->format('d-m-Y') : '' }}
                                                    </td>

                                                    <td class="text-center">
                                                        @can('edit_role')
                                                            <button type="button" class="btn btn-primary btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" title="Edit" data-bs-target="#editRole{{ $role->id }}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_role')
                                                            @if ($role->status == 1)
                                                                <a onclick="return confirm('Are you sure to inactivate?')" href="{{ route('admin.role.disable', $role->id) }}" title="Inactive" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                    <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @else
                                                                <a onclick="return confirm('Are you sure to activate?')" href="{{ route('admin.role.active', $role->id) }}" title="Active" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                                                    <i class="las la-check" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="editRole{{ $role->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Edit Role</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.role.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-xxl-12">
                                                                            <div>
                                                                                <label for="roleName" class="form-label">Role Name: <span style="color:red;">*</span></label>
                                                                                <input type="text" class="form-control" name="roleName" id="roleName" value="{{$role->display_name ?? '-'}}" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xxl-12">
                                                                            <div>
                                                                                <label for="roleName" class="form-label">Serial No: <span style="color:red;">*</span></label>
                                                                                <input type="number" class="form-control" name="sl" id="sl" placeholder="Serial" required value="{{ $role->sl }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                            </div>
                                                                        </div><!--end col-->
                                                                    </div><!--end row-->
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%" class="text-center"><b>No Data Found</b></td>
                                            </tr>
                                        @endif

                                    </tbody>
                                    <!-- end tbody -->
                                </table>
                                <!-- end table -->
                            </div>
                            <!-- end table responsive -->
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div>
        <!-- container-fluid -->
    </div>





<!-- Grids in modals -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
    Launch Demo Modal
</button> --}}
<div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('admin.role.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-xxl-12">
                            <div>
                                <label for="roleName" class="form-label">Role Name: <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="roleName" id="roleName" placeholder="Role Name" required>
                            </div>
                        </div>

                        <div class="col-xxl-12">
                            <div>
                                <label for="roleName" class="form-label">Serial No: <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" name="sl" id="sl" placeholder="Serial" required>
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
