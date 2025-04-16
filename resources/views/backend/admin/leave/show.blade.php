@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View Leave Application')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View Leave Application</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">View Leave Application</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View Leave Application</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="user_id" class="form-label">Employee: </label>

                                        <input type="text" class="form-control" id="user_id" value="{{ $leave->employee->name_en ?? '' }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="leave_category_id" class="form-label">Leave Category: </label>

                                        <input type="text" class="form-control" id="user_id" value="{{ $leave->category->name_en ?? '' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="from_date" class="form-label">From Date: </label>

                                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $leave->from_date }}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="to_date" class="form-label">To Date: </label>

                                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $leave->to_date }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="day_count" class="form-label">Total Days: </label>

                                        <input type="number" min="0" class="form-control" id="day_count" name="day_count" placeholder="Enter Total Days" value="{{ $leave->day_count }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="reason" class="form-label">Reason: </label>

                                        <textarea name="reason" id="reason" class="form-control" disabled>{{ $leave->reason }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                @can('hod_permission')
                                    @if ($leave->comment)
                                        <div class="col-md-6 col-sm-12">
                                            <div>
                                                <label for="comment" class="form-label">Deputy Manager's Comment: </label>

                                                <textarea name="comment" id="comment" class="form-control" disabled>{{ $leave->comment }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                @endcan

                                @if ($leave->hod_comment)
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="hod_comment" class="form-label">HOD Comment: </label>

                                            <textarea name="hod_comment" id="hod_comment" class="form-control" disabled>{{ $leave->hod_comment }}</textarea>
                                        </div>
                                    </div>
                                @endif

                                @if ($leave->leave_document)
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="file" class="form-label">Leave Document: </label>

                                            <a href="{{ asset('storage/leaveDocument') }}/{{ $leave->leave_document }}" class="btn btn-primary" download="">Download</a> 

                                            <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewFile{{ $leave->id }}">View</button>

                                            <div class="modal fade" id="viewFile{{ $leave->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalgridLabel">View Document</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div>
                                                                        @if((pathinfo('storage/leaveDocument/'.$leave->leave_document, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/leaveDocument/'.$leave->leave_document, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/leaveDocument/'.$leave->leave_document, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/leaveDocument/'.$leave->leave_document, PATHINFO_EXTENSION) == 'gif'))
                                                                            <img src="{{ asset('storage/leaveDocument') }}/{{ $leave->leave_document }}" style="width:100%;"/>
                                                                        @elseif (pathinfo('storage/leaveDocument/'.$leave->leave_document, PATHINFO_EXTENSION) == 'pdf')
                                                                            <iframe src="{{ asset('storage/leaveDocument') }}/{{ $leave->leave_document }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                        @else
                                                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/leaveDocument') }}/{{ $leave->leave_document }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                        
                                                                <div class="col-lg-12">
                                                                    <div class="hstack gap-2 justify-content-end">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div><!--end col-->
                                                            </div><!--end row-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                                
                            <div class="row mt-4">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="created_by" class="form-label">Created By: </label>

                                        <input type="text" class="form-control" id="created_by" name="created_by" placeholder="Enter Serial No." value="{{ $leave->createdBy->name_en ?? '' }} at {{ date('d M, Y h:i A', strtotime($leave->created_at)) }}" disabled>
                                    </div>
                                </div>

                                @if ($leave->updated_by)
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="updated_by" class="form-label">Updated By: </label>

                                            <input type="text" class="form-control" id="updated_by" name="updated_by" placeholder="Enter Serial No." value="{{ $leave->updatedBy->name_en ?? '' }} at {{ date('d M, Y h:i A', strtotime($leave->updated_at)) }}" disabled>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="created_by" class="form-label">Status: </label>

                                        @if ($leave->status == 2)
                                            <span class="badge bg-danger">Declined</span>
                                        @elseif($leave->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($leave->status == 3)
                                            <span class="badge bg-info">Forwarded</span>
                                        @else
                                            <span class="badge bg-primary">Pending</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    @can('leave_status_change')
                                        @if ($leave->status == 1 && ((Auth::user()->userInfo->designation_id ?? '') != 12))
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#declineStatus{{ $leave->id }}">Decline</button>
                                        @elseif ($leave->status == 2 && ((Auth::user()->userInfo->designation_id ?? '') != 12))
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveStatus{{ $leave->id }}">Approve</button>
                                        @elseif ($leave->status == 0 && ((Auth::user()->userInfo->designation_id ?? '') == 12) && (Auth::id() == ($leave->employee->team_id ?? '')))
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#forwardStatus{{ $leave->id }}">Forward</button>

                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#declineStatus{{ $leave->id }}">Decline</button>
                                        @elseif (($leave->status == 0 || $leave->status == 3) && ((Auth::user()->userInfo->designation_id ?? '') != 12))
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveStatus{{ $leave->id }}">Approve</button>

                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#declineStatus{{ $leave->id }}">Decline</button>
                                        @endif
                                    @endcan

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

                                    <div class="modal fade" id="forwardStatus{{ $leave->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalgridLabel">Are you sure want to forward?</h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <form action="{{ route('admin.leave.statusChange', Crypt::encryptString($leave->id)) }}" method="post">
                                                        @csrf

                                                        <input type="hidden" name="status" value="3">
                                                        <input type="hidden" name="leave_id" value="{{ $leave->id }}">

                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <div>
                                                                    <label for="comment" class="form-label">Comment</label>

                                                                    <textarea name="comment" id="comment" class="form-control" placeholder="Enter Comment">{{ $leave->comment }}</textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="hstack gap-2 justify-content-end">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    
                                                                    <button type="submit" class="btn btn-success">Forward</button>
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
                                </div>
                            </div>
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
@endsection

@push('script')
    <script>
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush