@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Project List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Project List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Project List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Project List</h4>

                            <div class="flex-shrink-0">
                                @can('add_project')
                                    <a class="btn btn-primary" href="{{ route('admin.project.create') }}">
                                        Add New Project
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
                                            <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{ $_GET['name'] }}" @endif type="text" class="form-control search" name="name" placeholder="Search by Project Name">
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
                                            <a style="max-width: 150px;" href="{{ route('admin.project.index') }}" class="btn btn-danger w-100"> 
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
                                            <th class="text-center">No.</th>
                                            <th>Project Name</th>
                                            <th>Project Category</th>
                                            <th>Authority Name</th>
                                            <th>Project Manager</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($projects->count() > 0)
                                            @php
                                                $i = ($projects->perPage() * ($projects->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($projects as $project)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $project->name ?? '-' }}</td>
                                                    <td>{{ $project->category->name ?? '-' }}</td>
                                                    <td>{{ $project->client->name_en ?? '-' }}</td>
                                                    <td>{{ $project->pm->name_en ?? '-' }}</td>
                                                    
                                                    <td>
                                                        @if ($project->status == 3)
                                                            <span class="badge bg-success">Completed</span>
                                                        @elseif($project->status == 1)
                                                            <span class="badge bg-info">On Going</span>
                                                        @elseif($project->status == 2)
                                                            <span class="badge bg-danger">Canceled</span>
                                                        @else
                                                            <span class="badge bg-primary">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @can('view_project')
                                                            <a href="{{ route('admin.project.show', Crypt::encryptString($project->id)) }}" title="View " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_project')
                                                            <a href="{{ route('admin.project.edit', Crypt::encryptString($project->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('delete_project')
                                                            @if ($project->status == 1)
                                                                <a onclick="return confirm('Are you sure, you want to inactive the project ?')" href="{{ route('admin.project.delete', Crypt::encryptString($project->id)) }}" title="Inactive" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                    <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @elseif ($project->status == 0)
                                                                <a onclick="return confirm('Are you sure, you want to initiate the project ?')" href="{{ route('admin.project.delete', Crypt::encryptString($project->id)) }}" title="Start" class="btn btn-sm btn-primary btn-icon waves-effect waves-light">
                                                                    <i class="las la-check" style="font-size: 1.6em;"></i>
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
                            {{ $projects->links() }}
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