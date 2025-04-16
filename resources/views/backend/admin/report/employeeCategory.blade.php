@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Category Wise Employee Report')
@section('content')
    @push('css')
        <style>
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
                        <h4 class="mb-sm-0">Category Wise Employee Summary</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Category Wise Employee Summary</li>
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
                            <h5 class="mb-0 flex-grow-1">
                                Category Wise Employee Summary
                            </h5>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        
                        <div class="card-body" id="printDiv" style="background: #fff !important;">
                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <table class="table" style="background: #fff !important;">
                                        <thead>
                                            <tr style="display: none;" class="head-logo">
                                                <th><img style="max-height: 60px;" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt=""></th>
                                            </tr>
        
                                            <tr class="text-center" style="border: none;">
                                                <th colspan="8" style="border: none;" class="py-4">
                                                    <h1>Category Wise Employee Summary</h1>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <div class="card">
                                        {{-- <div class="card-header">
                                            <h4>Category Wise Employee Report</h4>
                                        </div> --}}

                                        <div class="card-body">
                                            <table class="table table-bordered" style="background: #fff !important;">
                                                <tbody>
                                                    <tr>
                                                        <th>Category Name</th>
                                                        <th class="text-center">Employee Count</th>
                                                    </tr>

                                                    @foreach ($categories as $category)
                                                        <tr>
                                                            <td>{{ $category->name }}</td>
                                                            <td class="text-center">{{ $category->employee_info_count ?? 0 }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th>Total Employee</th>
                                                        <th class="text-center">{{ $categories->sum('employee_info_count') }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end row-->

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
                            <div class="col-md-1 col-sm-4">
                                <div>
                                    <button style="max-width: 150px;" class="btn btn-info w-100" onclick="printDiv('printDiv')"> 
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>Print
                                    </button>
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