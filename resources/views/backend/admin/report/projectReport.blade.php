@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Project Summary Report')
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
                        <h4 class="mb-sm-0">Project Summary Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Project Summary Report</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Project Summary Report</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{ $_GET['name'] }}" @endif type="text" class="form-control search" name="name" placeholder="Search by Project Name">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="category_id" id="category_id" class="form-control select2">
                                                <option value="">--Search by Project Category--</option>

                                                @foreach ($categorys as $category)
                                                    <option value="{{ $category->id }}" {{ (isset($_GET['category_id']) && ($_GET['category_id'] != '') && ($_GET['category_id'] == $category->id)) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="pm_id" id="pm_id" class="form-control select2">
                                                <option value="">--Search by Project Manager--</option>

                                                @foreach ($pms as $pm)
                                                    <option value="{{ $pm->id }}" {{ (isset($_GET['pm_id']) && ($_GET['pm_id'] != '') && ($_GET['pm_id'] == $pm->id)) ? 'selected' : '' }}>{{ $pm->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="client_id" id="client_id" class="form-control select2">
                                                <option value="">--Search by Client--</option>

                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}" {{ (isset($_GET['client_id']) && ($_GET['client_id'] != '') && ($_GET['client_id'] == $client->id)) ? 'selected' : '' }}>{{ $client->name_en }}</option>
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

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{ route('admin.report.projectReport') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row mt-4">
                                <div class="col-md-2 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" class="btn btn-info w-100" onclick="printDiv('printDiv')"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Print
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.exportProjectSummary') }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Excel
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.exportDocProjectSummary') }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Doc File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" id="printDiv" style="background: #fff !important;">
                            <div class="table-responsive" style="background: #fff !important;">

                                @include('backend.admin.report.tableData.projectReportData')
                                
                            </div>
                        </div>
                        <!-- end card body -->

                        <div class="card-footer">
                            {{-- {{ $projects->links() }} --}}
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

    {{-- <script src="https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js"></script>
    <script>
        function exportData() {
            $('.status').hide();
            $('.action').hide();
            $('.tfoot').hide();

            var table2excel = new Table2Excel();
            
            table2excel.export(document.getElementById("printExcel"));
            
            location.reload();
        }
    </script> --}}
@endpush