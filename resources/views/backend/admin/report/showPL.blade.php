@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Project P&L Report')
@section('content')
    @push('css')
        <style>
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

                th, td, p, span {
                    font-size: 14px;
                }

                .plBody {
                    margin-top: 0;
                }

                .plHeader th{
                    padding-top: 0;
                    padding-bottom: 0;
                }
                .plHeader h1{
                    padding-top: 20px;
                    font-size: 24px;
                }
                table {
                    margin-bottom: 0;
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
                        <h4 class="mb-sm-0">Project P&L Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Project P&L Report</li>
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
                            <h3 class="mb-0 flex-grow-1" style="font-weight: 800;">{{ $project->name }} Project P&L Report</h3>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        
                        <div class="card-body" id="printDiv" style="background: #fff !important;">
                            <table class="table mb-0" style="background: #fff !important;">
                                <thead>
                                    <tr style="display: none;" class="head-logo">
                                        <th><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
                                    </tr>

                                    <tr class="text-center plHeader" style="border: none;">
                                        <th colspan="8" style="border: none;" class="p-0">
                                            <h1>PROJECT P&L REPORT</h1>
                                        </th>
                                    </tr>
                                </thead>
                            </table>

                            <div class="row g-3 plBody" style="margin-top: 0px">
                                @php
                                    $totalEarning = 0;
                                    $totalExpense = 0;
                                    $netProfit = 0;
                                    $projectIncVatTax = 0;
                                    // $netProfit += $project_value->project_value ?? 0;
                                    // $netProfit = $netProfit - ($project_value->vat_tax ?? 0);
                                    $projectExVatTax = ($project_value->project_value ?? 0) - ($project_value->vat_tax ?? 0);
                                    
                                    if (count($projectEarnings) > 0) {
                                        foreach ($projectEarnings as $projectEarning) {
                                            $totalEarning += $projectEarning->amount ?? 0;
                                            $netProfit += $projectEarning->amount ?? 0;
                                        }
                                    }

                                    if (count($projectExpenses) > 0) {
                                        foreach ($projectExpenses as $projectExpense) {
                                            $totalExpense += $projectExpense->amount ?? 0;
                                            $netProfit = $netProfit - ($projectExpense->amount ?? 0);
                                        }
                                    }
                                @endphp

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Project Value v/s Project Expense</h4>
                                        </div>

                                        <div class="card-body">
                                            <table class="table table-bordered" style="background: #fff !important;">
                                                <tbody>
                                                    <tr>
                                                        <th>Project Value (Including Vat & Tax)</th>
                                                        <td>{{ number_format((float)($project_value->project_value ?? 0) ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    @foreach ($projectExpenses as $projectExpense)
                                                        <tr>
                                                            <td>{{ $projectExpense->purpose }}</td>
                                                            <td>{{ number_format((float)$projectExpense->amount ?? 0, 2, '.', '') }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th>Total Expense</th>
                                                        <td>{{ number_format((float)$totalExpense ?? 0, 2, '.', '') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Project Value v/s Project Earning</h4>
                                        </div>

                                        <div class="card-body">
                                            <table class="table table-bordered" style="background: #fff !important;">
                                                <tbody>
                                                    <tr>
                                                        <th>Project Value (Including Vat & Tax)</th>
                                                        <td>{{ number_format((float)($project_value->project_value ?? 0) ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Project Value (Excluding vat & tax)</th>
                                                        <td>{{ number_format((float)($projectExVatTax ?? 0) ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Vat and Tax</td>
                                                        <td>{{ number_format((float)($project_value->vat_tax ?? 0) ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total Earning</td>
                                                        <td>{{ number_format((float)$totalEarning ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total Expense</td>
                                                        <td>{{ number_format((float)$totalExpense ?? 0, 2, '.', '') }}</td>
                                                    </tr>

                                                    <tr class="bg-primary">
                                                        <td class="text-white">Net Profit</td>
                                                        <td class="text-white">{{ number_format((float)$netProfit ?? 0, 2, '.', '') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <p><span class="text-danger">*</span><span class="text-danger">*</span>Remarks: {{ $project_value->remarks ?? '' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="card-footer">
                                    <table>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <p>
                                                        ServicEngine Ltd.
                                                        <br>
                                                        <b>Branch Office:</b> 8 Abbas Garden | DOHS Mohakhali | Dhaka 1206 | Bangladesh | Phone: +88 (096) 0622-1100
                                                        <br>
                                                        <b>Corporate Office:</b> Monem Business District | Levell 7 | 111 Bir Uttam C.R. Dutta Road (Sonargaon Road) | Dhaka 1205 | Bangladesh | Phone: +88 (096) 0622-1176
                                                        <br>
                                                        sebpo.com
                                                    </p>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="row m-2">
                            <div class="col-md-2 col-sm-4">
                                <div>
                                    <button style="max-width: 150px;" class="btn btn-info w-100" onclick="printDiv('printDiv')"> 
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>Print
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-4">
                                <div>
                                    <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.excelExportPlReport', Crypt::encryptString($project->id)) }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Excel
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-4">
                                <div>
                                    <a style="max-width: 150px;" class="btn btn-info w-100" href="{{ route('admin.report.exportDocPlReport', Crypt::encryptString($project->id)) }}?@if(isset($_SERVER['QUERY_STRING'])){{$_SERVER['QUERY_STRING']}}@endif"> 
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>Export Doc File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    <script>
        function printDiv(divName) {
            $('.head-logo').show();

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush