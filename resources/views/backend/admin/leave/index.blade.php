@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Leave Application List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Leave Application List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Leave Application List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Leave Application List</h4>

                            <div class="flex-shrink-0">
                                @can('add_leave')
                                    <a class="btn btn-primary" href="{{ route('admin.leave.create') }}">
                                        New Leave Application
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    @if (Auth::user()->user_type != 3)
                                        <div class="col-md-2 col-sm-6">
                                            <div class="search-box">
                                                <select name="user_id" id="user_id" class="form-control select2" autocomplete="off">
                                                    <option value="">--Search by Employee--</option>

                                                    @foreach ($employees as $employee)
                                                        <option @if (isset($_GET['user_id']) and $_GET['user_id'] == $employee->id) selected @endif value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="leave_category_id" id="leave_category_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Leave Category--</option>

                                                @foreach ($categorys as $category)
                                                    <option @if (isset($_GET['leave_category_id']) and $_GET['leave_category_id'] == $category->id) selected @endif value="{{ $category->id }}">{{ $category->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['from_date']) && $_GET['from_date'] != '') value="{{ $_GET['from_date'] }}" @endif type="date" class="form-control search" name="from_date" placeholder="From Date">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
        
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['to_date']) && $_GET['to_date'] != '') value="{{ $_GET['to_date'] }}" @endif type="date" class="form-control search" name="to_date" placeholder="To Date">
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
                                            <a style="max-width: 150px;" href="{{ route('admin.leave.index') }}" class="btn btn-danger w-100"> 
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
                                            <th>Employee Name</th>
                                            <th>Leave Category</th>
                                            <th class="text-center">Total Days</th>
                                            <th class="text-center">From Date</th>
                                            <th class="text-center">To Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($leaves->count() > 0)
                                            @php
                                                $i = ($leaves->perPage() * ($leaves->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($leaves as $leave)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        {{ $leave->employee->name_en ?? '-' }}
                                                        <br>
                                                        (EID: {{ $leave->employee->userInfo->employee_id ?? '-' }})
                                                    </td>
                                                    <td>{{ $leave->category->name_en ?? '-' }}</td>
                                                    <td class="text-center">{{ $leave->day_count ?? '-' }}</td>
                                                    <td class="text-center">{{ date('d M, Y', strtotime($leave->from_date)) }}</td>
                                                    <td class="text-center">{{ date('d M, Y', strtotime($leave->to_date)) }}</td>
                                                    
                                                    <td class="text-center">
                                                        @if ($leave->status == 2)
                                                            <span class="badge bg-danger">Declined</span>
                                                        @elseif($leave->status == 1)
                                                            <span class="badge bg-success">Approved</span>
                                                        @elseif($leave->status == 3)
                                                            <span class="badge bg-info">Forwarded</span>
                                                        @else
                                                            <span class="badge bg-primary">Pending</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @can('show_leave')
                                                            <a href="{{ route('admin.leave.show', Crypt::encryptString($leave->id)) }}" title="View " type="button" class="btn btn-primary btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_leave')
                                                            <a href="{{ route('admin.leave.edit', Crypt::encryptString($leave->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        {{-- @can('leave_status_change')
                                                            @if ($leave->status == 1)
                                                                <button title="Decline" id="#declineStatus{{ $leave->id }}" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#declineStatus{{ $leave->id }}">
                                                                    <i class="las la-ban" style="font-size: 1.6em;"></i>
                                                                </button>
                                                            @elseif ($leave->status == 2)
                                                                <button id="#approveStatus{{ $leave->id }}" title="Approve" class="btn btn-sm btn-success btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#approveStatus{{ $leave->id }}">
                                                                    <i class="las la-check" style="font-size: 1.6em;"></i>
                                                                </button>
                                                            @else
                                                                <button id="#approveStatus{{ $leave->id }}" title="Approve" class="btn btn-sm btn-success btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#approveStatus{{ $leave->id }}">
                                                                    <i class="las la-check" style="font-size: 1.6em;"></i>
                                                                </button>

                                                                <button id="#declineStatus{{ $leave->id }}" title="Decline" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#declineStatus{{ $leave->id }}">
                                                                    <i class="las la-ban" style="font-size: 1.6em;"></i>
                                                                </button>
                                                            @endif
                                                        @endcan --}}

                                                        @can('delete_leave')
                                                            <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.leave.delete', Crypt::encryptString($leave->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="approveStatus{{ $leave->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Are you sure want to approve?</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.leave.statusChange', Crypt::encryptString($leave->id)) }}" method="post">
                                                                    @csrf

                                                                    <input type="hidden" name="status" value="1">
                                                                    <input type="hidden" name="leave_id" value="{{ $leave->id }}">

                                                                    <div class="row g-3">
                                                                        <div class="col-md-12">
                                                                            <div>
                                                                                <label for="hod_comment" class="form-label">HOD Comment</label>
        
                                                                                <textarea name="hod_comment" id="hod_comment" class="form-control" placeholder="Enter HOD Comment">{{ $leave->hod_comment }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                                
                                                                                <button type="submit" class="btn btn-success">Approve</button>
                                                                            </div>
                                                                        </div><!--end col-->
                                                                    </div><!--end row-->
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="declineStatus{{ $leave->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Are you sure want to decline?</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.leave.statusChange', Crypt::encryptString($leave->id)) }}" method="post">
                                                                    @csrf

                                                                    <input type="hidden" name="status" value="2">
                                                                    <input type="hidden" name="leave_id" value="{{ $leave->id }}">

                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="hod_comment" class="form-label">HOD Comment</label>

                                                                                <textarea name="hod_comment" id="hod_comment" class="form-control" placeholder="Enter HOD Comment">{{ $leave->hod_comment }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                                
                                                                                <button type="submit" class="btn btn-danger">Decline</button>
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
                            {{ $leaves->links() }}
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

