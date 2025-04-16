@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Month-wise Leave Summary')

@push('css')
    <style>
        th, td, p, span {
            font-size: 14px;
        }

        table { 
            page-break-inside: auto; 
        }
        tr { 
            page-break-inside: avoid; 
            page-break-after: auto;
        }
        thead { 
            display: table-header-group; 
        }
        @media screen {
            tfoot {
                display: none;
            }
        }

        @media print {
            tfoot { 
                display: table-footer-group; 
                /* position: fixed;
                bottom: 0; */
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Month-wise Leave Summary {{ (isset($_GET['year']) && ($_GET['year'] != '')) ? $_GET['year'] : date('Y') }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Month-wise Leave Summary {{ (isset($_GET['year']) && ($_GET['year'] != '')) ? $_GET['year'] : date('Y') }}</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Month-wise Leave Summary {{ (isset($_GET['year']) && ($_GET['year'] != '')) ? $_GET['year'] : date('Y') }}</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['employee_id']) and $_GET['employee_id'] != '') value="{{ $_GET['employee_id'] }}" @endif type="text" class="form-control search" name="employee_id" placeholder="Search by EID">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

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
                                            <select name="year" id="year" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Year--</option>

                                                @for ($i = 2006; $i <= date('Y'); $i++)
                                                    <option @if (isset($_GET['year']) and $_GET['year'] == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                                @endfor
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
                                            <a style="max-width: 150px;" href="{{ route('admin.leave.leaveSummaryMonthwise') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" class="btn btn-info w-100" onclick="printDiv('printDiv')"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body" id="printDiv" style="background: #fff !important;">
                            <div class="table-responsive" style="background: #fff !important;">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr style="border: none; display: none;" class="reportLogo">
                                            <th colspan="8" style="border: none;"><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
                                        </tr>

                                        <tr>
                                            <th rowspan="2" class="text-center">#</th>
                                            <th rowspan="2">EID</th>
                                            <th rowspan="2">Employee Name</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">January</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">February</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">March</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">April</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">May</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">June</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">July</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">August</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">September</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">October</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">November</th>
                                            <th colspan="{{ count($categorys) }}" class="text-center">December</th>
                                        </tr>

                                        <tr>
                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach

                                            @foreach ($categorys as $category)
                                                <th class="text-center">Taken {{ $category->name_en }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($leaves->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($leaves as $leave)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $leave->userInfo->employee_id ?? '-' }}</td>
                                                    <td>{{ $leave->name_en ?? '-' }}</td>
                                                    
                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 1)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 2)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 3)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 4)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 5)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 6)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 7)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 8)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 9)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 10)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 11)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp

                                                    @php
                                                        foreach ($categorys as $category) {
                                                            $totalTaken = App\Models\Leave::where('leave_category_id', $category->id)->where('status', 1)->where('user_id', $leave->id)->whereMonth('from_date', 12)->whereYear('from_date', date('Y'))->sum('day_count');

                                                            echo "<td class='text-center'>".($totalTaken ?? 0)."</td>";
                                                        }
                                                    @endphp
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

                                    <tfoot class="tfoot">
                                        <tr style="border: none;">
                                            <td colspan="8" style="border: none;">
                                                <p style="font-size: 13px;">
                                                    <b style="font-size: 16px;">ServicEngine Ltd.</b>
                                                    <br>
                                                    <b>Branch Office:</b> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100
                                                    <br>
                                                    <b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) 
                                                    <br> 
                                                    Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176
                                                    <br>
                                                    sebpo.com
                                                </p>
                                            </td>
                                        </tr>
                                    </tfoot>
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
@endsection

@push('script')
    <script>
        function printDiv(divName) {
            $('.status').hide();
            $('.action').hide();
            $('.reportLogo').show();
            $('body').css('background', '#fff');

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush