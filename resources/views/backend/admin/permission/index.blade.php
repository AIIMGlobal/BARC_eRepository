@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Permission List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Permission List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Permission List</li>
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
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Permission List</h4>

                            <div class="flex-shrink-0">
                                @can('add_permission')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewPermission">
                                        Add New Permission
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <form class="m-3">
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name_en']) and $_GET['name_en']!='') value="{{ $_GET['name_en'] }}" @endif type="text" class="form-control search" name="name_en" placeholder="Search by Permission Name">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Filter
                                        </button>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" href="{{ route('admin.permission.index') }}" class="btn btn-danger w-100"> 
                                            <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Permission Name</th>
                                            <th>Created By</th>
                                            <th class="text-center">Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($permissions->count() > 0)
                                            @php
                                                $i = ($permissions->perPage() * ($permissions->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $permission->name_en ?? '-' }}</td>
                                                    <td>{{ $permission->createdUser->full_name ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $permission->created_at ? $permission->created_at->format('d-m-Y') : '-' }}
                                                    </td>

                                                    <td class="text-center">
                                                        @can('edit_permission')
                                                            <button type="button" class="btn btn-sm btn-primary btn-icon waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#editPermission{{ $permission->id }}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_permission')
                                                            <a onclick="return confirm('Are you sure want to delete?')" href="{{ route('admin.permission.delete', $permission->id) }}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="editPermission{{ $permission->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Edit Permission</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.permission.update', $permission->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-lg-12">
                                                                            <div>
                                                                                <label for="lastName" class="form-label">Permission Name: <span style="color:red;">*</span></label>

                                                                                <input type="text" class="form-control" name="name_en" placeholder="Enter Permission Name" value="{{ $permission->name_en }}" required>
                                                                            </div>
                                                                        </div><!--end col-->

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

                        <div class="card-footer">
                            {{ $permissions->links() }}
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

    <div class="modal fade" id="addNewPermission" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Add New Permission</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.permission.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="lastName" class="form-label">Permission Name: <span style="color:red;">*</span></label>

                                    <input type="text" class="form-control" name="name_en" placeholder="Enter Permission Name" required>
                                </div>
                            </div><!--end col-->

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
