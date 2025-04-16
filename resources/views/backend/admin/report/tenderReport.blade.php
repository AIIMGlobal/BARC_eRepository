@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Tender Report')
@section('content')
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

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Tender Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Tender Report</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Tender Report</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-1 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['work_title']) and $_GET['work_title']!='') value="{{$_GET['work_title']}}" @endif type="text" class="form-control search" name="work_title" placeholder="Search by Work Title">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="tender_type_id" id="tender_type_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Tender Type--</option>

                                                @foreach ($tenderTypes as $tenderType)
                                                    <option @if (isset($_GET['tender_type_id']) and $_GET['tender_type_id'] == $tenderType->id) selected @endif
                                                        value="{{ $tenderType->id }}">{{ $tenderType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="client_id" id="client_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Authority--</option>

                                                @foreach ($clients as $client)
                                                    <option @if (isset($_GET['client_id']) and $_GET['client_id'] == $client->id) selected @endif
                                                        value="{{ $client->id }}">{{ $client->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="status" id="status" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Status--</option>

                                                <option value="0" {{ (isset($_GET['status']) and $_GET['status'] == 0) ? 'selected' : '' }}>Upcoming</option>
                                                <option value="1" {{ (isset($_GET['status']) and $_GET['status'] == 1) ? 'selected' : '' }}>EOI Submitted</option>
                                                <option value="2" {{ (isset($_GET['status']) and $_GET['status'] == 2) ? 'selected' : '' }}>EOI Not Submitted</option>
                                                <option value="3" {{ (isset($_GET['status']) and $_GET['status'] == 3) ? 'selected' : '' }}>Shortlisted</option>
                                                <option value="4" {{ (isset($_GET['status']) and $_GET['status'] == 4) ? 'selected' : '' }}>Not Shortlisted</option>
                                                <option value="7" {{ (isset($_GET['status']) and $_GET['status'] == 7) ? 'selected' : '' }}>RFP Submitted</option>
                                                <option value="8" {{ (isset($_GET['status']) and $_GET['status'] == 8) ? 'selected' : '' }}>RFP Not Submitted</option>
                                                <option value="5" {{ (isset($_GET['status']) and $_GET['status'] == 5) ?'selected' : ''}}>Project Owned</option>
                                                <option value="6" {{ (isset($_GET['status']) and $_GET['status'] == 6) ? 'selected' : '' }}>Declined</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['from_date']) and $_GET['from_date']!='') value="{{$_GET['from_date']}}" @endif type="date" class="form-control search" name="from_date" placeholder="From Date">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
        
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['to_date']) and $_GET['to_date']!='') value="{{$_GET['to_date']}}" @endif type="date" class="form-control search" name="to_date" placeholder="To Date">
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

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{ route('admin.report.tenderReport') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" class="btn btn-info w-100" onclick="printDiv('printDiv')"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Print
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.excelExportTenderReport') }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Excel
                                            </a>
                                        </div>
                                    </div>
    
                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.exportDocTenderReport') }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Doc File
                                            </a>
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

                                        <tr class="text-center" style="border: none;">
                                            <th colspan="8" style="border: none;" class="py-4">
                                                <h1>Tender REPORT</h1>
                                            </th>
                                        </tr>

                                        <tr style="background: #090979; color: #fff;">
                                            <th class="text-center">#</th>
                                            <th class="text-center">Date of Submission</th>
                                            <th>Tender Type</th>
                                            <th>Work Title</th>
                                            <th>Lot</th>
                                            <th>Name of Authority</th>
                                            <th>Organiztion Status-Lead Partner (If JV)</th>
                                            <th>Assignees</th>
                                            <th>Concerned Person</th>
                                            <th class="text-center status">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($tenders->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($tenders as $tender)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td class="text-center">{{ date('d M, Y', strtotime($tender->date_of_submission)) }}</td>
                                                    <td>{{ $tender->tenderType->name ?? '-' }}</td>
                                                    <td>{{ $tender->work_title }}</td>
                                                    <td>{{ $tender->lot ?? '-' }}</td>
                                                    <td>{{ $tender->client->name_en ?? '-' }}</td>
                                                    <td>{{ $tender->organization_jv_name }}</td>

                                                    <td>
                                                        @php
                                                            if ($tender->employee_ids) {
                                                                $eoiIds = explode(',', $tender->employee_ids);
                                                                $eois = App\Models\User::whereIn('id', $eoiIds)->get();
                                                            } else {
                                                                $eois = [];
                                                            }

                                                            if ($tender->rfp_assigness) {
                                                                $rfpIds = explode(',', $tender->rfp_assigness);
                                                                $rfps = App\Models\User::whereIn('id', $rfpIds)->get();
                                                            } else {
                                                                $rfps = [];
                                                            }
                                                        @endphp

                                                        @if (count($eois) > 0)
                                                            EOI Assigness:  @foreach ($eois as $eoi)
                                                                                @if ($loop->last)
                                                                                    {{ $eoi->name_en }} 
                                                                                @else
                                                                                    {{ $eoi->name_en }}, 
                                                                                @endif
                                                                            @endforeach
                                                        @endif

                                                        <br>

                                                        @if (count($rfps) > 0)
                                                            RFP Assigness:  @foreach ($rfps as $rfp)
                                                                                @if ($loop->last)
                                                                                    {{ $rfp->name_en }} 
                                                                                @else
                                                                                    {{ $rfp->name_en }}, 
                                                                                @endif
                                                                            @endforeach
                                                        @endif
                                                    </td>

                                                    <td>{{ $tender->concerned_person ?? '-' }}</td>
                                                    
                                                    <td class="text-center">
                                                        @foreach (tender_status() as $index => $status)
                                                            @if ($index == $tender->status)
                                                                <span class="badge bg-success">{{ $status }}</span>
                                                            @endif
                                                        @endforeach
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

                                    <tfoot>
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
            $('.action').hide();
            $('.reportLogo').show();

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush