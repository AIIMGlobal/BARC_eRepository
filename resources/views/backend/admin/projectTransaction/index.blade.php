@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Project Transaction List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Project Transaction List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Project Transaction List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Project Transaction List</h4>
                            
                            <div class="flex-shrink-0">
                                @can('create_project_transaction')
                                    <a class="btn btn-primary" href="{{route('admin.project_transaction.create')}}">
                                        Add New Project Transaction
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="project_id" id="project_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Project--</option>

                                                @foreach ($projects as $project)
                                                    <option @if (isset($_GET['project_id']) and $_GET['project_id'] == $project->id) selected @endif
                                                        value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>
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
                                            <a style="max-width: 150px;" href="{{ route('admin.project_transaction.index') }}" class="btn btn-danger w-100"> 
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
                                            <th>Transaction Type</th>
                                            <th>Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($projectTransactions->count() > 0)
                                            @php
                                                $i = ($projectTransactions->perPage() * ($projectTransactions->currentPage() - 1) +1);
                                            @endphp
                                            
                                            @foreach ($projectTransactions as $projectTransaction)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $projectTransaction->project->name ?? '-' }}</td>

                                                    <td>{{ $projectTransaction->type == 1 ? 'Project Earning' : 'Project Expense' }}</td>

                                                    <td>{{ number_format((float)$projectTransaction->amount?? 0, 2, '.', '') }}</td>
                                                    
                                                    <td class="text-center">
                                                        @can('show_project_transaction')
                                                            <a href="{{ route('admin.project_transaction.show', Crypt::encryptString($projectTransaction->id)) }}" title="View" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_project_transaction')
                                                            <a href="{{ route('admin.project_transaction.edit', Crypt::encryptString($projectTransaction->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('delete_project_transaction')
                                                            <a onclick="return confirm('Are you sure want to delete ?')" href="{{ route('admin.project_transaction.delete', Crypt::encryptString($projectTransaction->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
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
                            {{ $projectTransactions->links() }}
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

