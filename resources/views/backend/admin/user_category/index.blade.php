@extends('backend.layouts.app')

@section('title', 'Service Type | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Service Type</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>

                                <li class="breadcrumb-item active">Service Type</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Service Type</h4>

                            <div class="flex-shrink-0">
                                @can('add_user_category')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewHost">
                                        Add New Service Type
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['name']) and $_GET['name'] != '') value="{{ $_GET['name'] }}" @endif type="text" class="form-control search" name="name" placeholder="Service Type Name">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Filter
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{ route('admin.user_category.index') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Service Type Name</th>
                                            <th class="text-center">Status</th>
                                            {{-- <th>{{__('pages.Created By')}}</th> --}}
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($user_categories->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($user_categories as $user_category)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>

                                                    <td>{{ $user_category->name }}</td>

                                                    <td class="text-center">
                                                        @if ($user_category->status == 1)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>

                                                    {{-- <td>{{ $user_category->createdBy->name_en ?? '-' }}</td> --}}

                                                    <td class="text-center">
                                                        @can('edit_user_category')
                                                            <button title="Edit" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#edituser_category{{$user_category->id}}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_user_category')
                                                            <a onclick="return confirm('Are You sure..?')" href="{{route('admin.user_category.delete', $user_category->id)}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="edituser_category{{ $user_category->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Edit Service Type</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{route('admin.user_category.update', $user_category->id)}}" method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="name" class="form-label">Service Type Name<span style="color:red;">*</span></label>
                                                                                <input type="text" class="form-control" name="name" value="{{ $user_category->name }}" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="sl" class="form-label">Service Type Serial</label>

                                                                                <input type="text" class="form-control" name="sl" value="{{ $user_category->sl }}" placeholder="Enter Service Type Serial">
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-lg-12">
                                                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                                                <input @if($user_category->status == 1) checked @endif class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1">
                                                                                <label class="form-check-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%" class="text-center"><b>{{__('pages.No Data Fount')}}</b></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <!-- end tbody -->
                                </table>
                                <!-- end table -->

                                <div class="mt-3">
                                    {{ $user_categories->appends($_GET)->links() }}
                                </div>
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

    <div class="modal fade" id="addNewHost" tabindex="-1" aria-labelledby="addNewHostLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewHostLabel">Add New Service Type</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('admin.user_category.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-12">
                                <div>
                                    <label for="name" class="form-label">Service Type Name<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Service Type Name" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div>
                                    <label for="sl" class="form-label">Service Type Serial</label>
                                    <input type="text" class="form-control" name="sl" placeholder="Enter Service Type Serial">
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>
                                    <label class="form-check-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('pages.Cancel')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('pages.Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
