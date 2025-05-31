@extends('backend.layouts.app')

@section('title', 'User Report (Organization-wise) | ' . ($global_setting->title ?? ""))

@section('content')
    @push('css')
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 15mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12pt;
                }
                .card, .card-header, .card-footer {
                    border: none !important;
                }
                .head-logo {
                    display: table-row !important;
                }
                tfoot {
                    display: table-footer-group;
                }
                .no-print {
                    display: none;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                }
                h1 {
                    font-size: 16pt;
                    margin-bottom: 20px;
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
                        {{-- <h4 class="mb-sm-0">User Report (Organization-wise)</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">User Report (Organization-wise)</li>
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
                                User Report (Organization-wise)
                            </h5>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body" id="printDiv" style="background: #fff !important;">
                            <div class="row g-3 no-print">
                                <div class="col-md-12">
                                    <form id="filterForm">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="organization" class="form-label">Organization</label>

                                                <select name="organization" id="organization" class="form-select select2">
                                                    <option value="">Select Organization</option>

                                                    @foreach($orgs as $org)
                                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="designation" class="form-label">Designation</label>

                                                <select name="designation" id="designation" class="form-select select2">
                                                    <option value="">Select Designation</option>

                                                    @foreach($designations as $designation)
                                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="service_type" class="form-label">User Service Type</label>

                                                <select name="service_type" id="service_type" class="form-select select2">
                                                    <option value="">Select User Service Type</option>

                                                    @foreach($categorys as $service_type)
                                                        <option value="{{ $service_type->id }}">{{ $service_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="button" class="btn btn-primary" id="filterBtn">Filter</button>

                                                <button type="button" class="btn btn-danger" id="resetBtn">Reset</button>

                                                <button style="max-width: 150px;" class="btn btn-info" onclick="printDiv('printDiv')"> 
                                                    Print
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <table class="table" style="background: #fff !important;">
                                        <thead>
                                            <tr class="head-logo" style="display: none;">
                                                <th style="border: none;"><img style="max-height: 100px;" src="{{ asset('storage/soft_logo/' . ($global_setting->soft_logo ?? '')) }}" alt=""></th>
                                            </tr>
                                            
                                            <tr class="text-center tableHeading" style="border: none; display:none;">
                                                <th colspan="8" style="border: none;">
                                                    <h1>User Report (Organization-wise)</h1>
                                                    <h3><strong>Total Result: <span id="totalCountDisplay">0</span></strong></h3>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-bordered" style="background: #fff !important;" id="reportTable">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Organization Name</th>
                                                        <th>User</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Designation</th>
                                                        <th>User Service Type</th>
                                                        <th class="actionBtn text-center">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="reportTableBody">
                                                    
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
                                                <td style="border: none;">
                                                    <p>
                                                        Bangladesh Agricultural Research Council (BARC)
                                                        <br>
                                                        <b>Email:</b> info@barc.gov.bd
                                                        <br>
                                                        <b>Location:</b> Q95Q+RGX | Khamarbari Rd | Dhaka 1215 | Bangladesh | Phone: +8802-41025252
                                                        <br>
                                                        barc.gov.bd
                                                    </p>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable({
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                responsive: true,
                pageLength: 100,
                order: [[0, 'asc']],
                language: {
                    emptyTable: '<div class="alert alert-info text-center">Please apply filters to view data.</div>'
                }
            });

            // $('#organization, #designation, #service_type').on('change keyup', function() {
            //     fetchFilteredData();
            // });

            $('#filterBtn').on('click', function() {
                fetchFilteredData();
            });

            $('#resetBtn').on('click', function() {
                $('#filterForm')[0].reset();
                $('#organization, #designation, #service_type').val('').trigger('change');
                $('#reportTableBody').html('<tr><td colspan="8" class="text-center"><div class="alert alert-info text-center">Please apply filters to view data.</div></td></tr>');
                $('#totalCountDisplay').text('0');
                $('.tableHeading').hide();

                // fetchFilteredData();
            });

            function fetchFilteredData() {
                const organization = $('#organization').val();
                const designation = $('#designation').val();
                const service_type = $('#service_type').val();

                $.ajax({
                    url: "{{ route('admin.report.orgUserReport') }}",
                    type: "GET",
                    data: {
                        organization: organization,
                        designation: designation,
                        service_type: service_type
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#reportTableBody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                        $('#totalCountDisplay').text('0');
                        $('.tableHeading').hide();
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.html != '') {
                                $('#reportTableBody').html(response.html);
                                 $('#totalCountDisplay').text(response.totalCount);
                                 $('.tableHeading').show();
                            } else {
                                $('#reportTableBody').html('<tr><td colspan="7" class="text-center">No data found</td></tr>');
                                $('#totalCountDisplay').text('0');
                                $('.tableHeading').hide();
                            }
                        } else {
                            $('#reportTableBody').html('<tr><td colspan="7" class="text-center">No data found</td></tr>');
                            $('#totalCountDisplay').text('0');
                            $('.tableHeading').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#reportTableBody').html('<tr><td colspan="7" class="text-center text-danger">An error occurred</td></tr>');
                        $('#totalCountDisplay').text('0');
                        $('.tableHeading').hide();
                    }
                });
            }
        });

        function printDiv(divName) {
            $('.head-logo').show();
            $('.card-footer').show();
            $('.tableHeading').show();
            $('.actionBtn').hide();
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