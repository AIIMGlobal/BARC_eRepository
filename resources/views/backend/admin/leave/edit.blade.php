@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Edit Leave Application')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit Leave Application</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Edit Leave Application</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit Leave Application</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        @if (Auth::user()->user_type == 3)
                            <div class="card-header">
                                <h4 class="card-title mb-0 flex-grow-1">Leave Details</h4>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Leave Category</th>
                                                    <th class="text-center">Maximum Countable Days</th>
                                                    <th class="text-center">Leave Remain</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $remain = 0;
                                                @endphp

                                                @foreach ($categorys as $category)
                                                    @php
                                                        $leaveDetails = App\Models\Leave::where('user_id', Auth::id())
                                                                            ->where('leave_category_id', $category->id)
                                                                            ->whereYear('from_date', date('Y'))
                                                                            ->where('status', 1)->sum('day_count');

                                                        $remain = ($category->day_number ?? 0)  - ($leaveDetails ?? 0);
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $category->name_en }}</td>
                                                        <td class="text-center">{{ $category->day_number ?? 0 }}</td>
                                                        <td class="text-center {{ $remain > $category->day_number ? 'text-danger' : '' }}">{{ $remain == $category->day_number ? ($category->day_number ?? 0) : ($remain ?? 0) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <form action="{{ route('admin.leave.update', Crypt::encryptString($leave->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="user_id" class="form-label">Employee: <span style="color:red;">*</span></label>

                                            @can('leave_for_others')
                                                <select name="user_id" id="user_id" class="form-control select2" required>
                                                    <option value="">--Select Employee--</option>

                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}" {{ $leave->user_id == $employee->id ? 'selected' : '' }}>{{ $employee->name_en }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="hidden" class="form-control" name="user_id" value="{{ Auth::id() }}">
                                                <input type="text" class="form-control" id="user_id" value="{{ Auth::user()->name_en }}" disabled>
                                            @endcan
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="leave_category_id" class="form-label">Leave Category: <span style="color:red;">*</span></label>

                                            <select name="leave_category_id" id="leave_category_id" class="form-control select2" required>
                                                <option value="">--Select Leave Category--</option>

                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}" {{ $leave->leave_category_id == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="from_date" class="form-label">From Date: <span style="color:red;">*</span></label>

                                            <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $leave->from_date }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="to_date" class="form-label">To Date: <span style="color:red;">*</span></label>

                                            <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $leave->to_date }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="day_count" class="form-label">Total Days: <span style="color:red;">*</span></label>

                                            <input type="number" min="0" class="form-control" id="day_count" name="day_count" placeholder="Enter Total Days" value="{{ $leave->day_count }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="reason" class="form-label">Reason: <span style="color:red;">*</span></label>

                                            <textarea name="reason" id="reason" class="form-control" required>{{ $leave->reason }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    @can('leave_status_change')
                                        <div class="col-md-6 col-sm-12">
                                            <div>
                                                <label for="hod_comment" class="form-label">HOD Comment: </label>

                                                <textarea name="hod_comment" id="hod_comment" class="form-control">{{ $leave->hod_comment }}</textarea>
                                            </div>
                                        </div>
                                    @endcan

                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="leave_document" class="form-label">Leave Document: </label>

                                            @if ($leave->leave_document)
                                                <a href="{{ asset('storage/leaveDocument') }}/{{ $leave->leave_document }}" class="btn btn-primary" download="">Download</a> 

                                                <br>
                                            @endif

                                            <input type="file" class="form-control mt-4" id="leave_document" name="leave_document">
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="row mt-4">
                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>
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