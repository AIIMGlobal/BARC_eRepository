@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Leave Category List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Leave Category List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Leave Category List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Leave Category List</h4>

                            <div class="flex-shrink-0">
                                @can('create_leave_category')
                                    <a class="btn btn-primary" href="{{ route('admin.leaveCategory.create') }}">
                                        Add New Leave Category
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-xxl-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['name_en']) and $_GET['name_en']!='') value="{{$_GET['name_en']}}" @endif type="text" class="form-control search" name="name_en" placeholder="Search by Leave Category">
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
                                            <a style="max-width: 150px;" href="{{ route('admin.leaveCategory.index') }}" class="btn btn-danger w-100"> 
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
                                            <th>Name</th>
                                            <th class="text-center">Maximum Countable Day</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($categorys->count() > 0)
                                            @php
                                                $i = ($categorys->perPage() * ($categorys->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($categorys as $category)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $category->name_en ?? '-' }}</td>
                                                    <td class="text-center">{{ $category->day_number ?? '-' }}</td>
                                                    
                                                    <td class="text-center">
                                                        @if ($category->status == 0)
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @elseif($category->status == 1)
                                                            <span class="badge bg-success">Active</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @can('show_leave_category')
                                                            <a href="{{ route('admin.leaveCategory.show', Crypt::encryptString($category->id)) }}" title="View" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_leave_category')
                                                            <a href="{{ route('admin.leaveCategory.edit', Crypt::encryptString($category->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('delete_leave_category')
                                                            <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.leaveCategory.delete', Crypt::encryptString($category->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp
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
                            {{ $categorys->links() }}
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
@endsection