@extends('backend.layouts.app')

@section('title', 'Content Report (Organization-wise) | ' . ($global_setting->title ?? ""))

@section('content')
    @push('css')
        <style>
            @media screen {
                /* tfoot {
                    display: none;
                } */
                .head-logo {
                    display: none;
                }
            }

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
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Content Report (Organization-wise)</li>
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
                                Content Report (Organization-wise)
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
                                                <label for="category" class="form-label">Category</label>

                                                <select name="category" id="category" class="form-select select2">
                                                    <option value="">Select Category</option>

                                                    @foreach($categorys as $category)
                                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="user_id" class="form-label">User</label>

                                                <select name="user_id" id="user_id" class="form-select select2">
                                                    <option value="">Select User</option>

                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name_en }}</option>
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
                                            <tr class="head-logo">
                                                <th style="border: none;"><img style="max-height: 100px;" src="{{ asset('storage/soft_logo/' . ($global_setting->soft_logo ?? '')) }}" alt=""></th>
                                            </tr>

                                            <tr class="text-center tableHeading" style="border: none; display:none;">
                                                <th colspan="8" style="border: none;" class="py-4">
                                                    <h1>Content Report (Organization-wise)</h1>
                                                    <h3><strong>Total Result: {{ $totalCount ?? 0 }}</strong></h3>
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
                                                        <th>Content Title</th>
                                                        <th>Category</th>
                                                        <th>Created By</th>
                                                        <th class="text-center">Content Count</th>
                                                        <th class="actionBtn text-center">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="reportTableBody">
                                                    {{-- <tr><td colspan="7" class="text-center"><div class="alert alert-info text-center">Please apply filters to view data.</div></td></tr> --}}
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" style="text-align: right;"><b>Total: </b></td>
                                                        <td class="text-center" id="totalContentCount"><b>0</b></td>
                                                        <td class="actionBtn"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end row-->

                            <div class="row">
                                <div class="card-footer" style="display: none;">
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
            let table = null;

            // function initializeDataTable() {
            //     if (table) {
            //         table.destroy();
            //     }
            //     table = $('#reportTable').DataTable({
            //         searching: false,
            //         paging: false,
            //         ordering: false,
            //         info: false,
            //         responsive: true,
            //         pageLength: 100,
            //         order: [[0, 'asc']],
            //         columnDefs: [
            //             { orderable: false, targets: [-1] }
            //         ],
            //         language: {
            //             emptyTable: '<div class="alert alert-info text-center">Please apply filters to view data.</div>'
            //         }
            //     });
            // }

            // initializeDataTable();

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

            // $('#filterBtn').on('click', function() {
            //     if (!$('#organization').val() && !$('#category').val() && !$('#user_id').val()) {
            //         $('#reportTableBody').html('<tr><td colspan="7" class="text-center"><div class="alert alert-info text-center">Please apply filters to view data.</div></td></tr>');
            //         $('#totalContentCount').text('0');

            //         initializeDataTable();

            //         return;
            //     }

            //     fetchFilteredData();
            // });

            $('#filterBtn').on('click', function() {
                fetchFilteredData();
            });

            $('#resetBtn').on('click', function() {
                $('#filterForm')[0].reset();
                $('#organization, #category, #user_id').val('').trigger('change');
                $('#reportTableBody').html('<tr><td colspan="7" class="text-center"><div class="alert alert-info text-center">Please apply filters to view data.</div></td></tr>');
                $('#totalContentCount').text('0');

                // initializeDataTable();
            });

            function fetchFilteredData() {
                const organization = $('#organization').val();
                const category = $('#category').val();
                const user_id = $('#user_id').val();

                $.ajax({
                    url: "{{ route('admin.report.orgContentReport') }}",
                    type: "GET",
                    data: {
                        organization: organization,
                        category: category,
                        user_id: user_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#reportTableBody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                        $('#totalContentCount').text('0');
                    },
                    success: function(response) {
                        if (response.success && response.html) {
                            $('#reportTableBody').html(response.html);
                            $('#totalContentCount').text(response.totalCount);
                        } else {
                            $('#reportTableBody').html('<tr><td colspan="7" class="text-center">No data found</td></tr>');
                            $('#totalContentCount').text('0');
                        }
                        
                        // initializeDataTable();
                    },
                    error: function(xhr, status, error) {
                        $('#reportTableBody').html('<tr><td colspan="7" class="text-center text-danger">An error occurred</td></tr>');
                        $('#totalContentCount').text('0');

                        // initializeDataTable();
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