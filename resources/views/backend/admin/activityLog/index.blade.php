@extends('backend.layouts.app')

@section('title', 'Activity Log | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Activity Log</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Activity Log</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="type" id="type" class="form-control select2">
                                                <option value="">--Search by Activity Type--</option>

                                                @foreach ($activityTypes as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <input type="date" name="from_date" id="from_date" class="form-control" placeholder="From Date">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-4">
                                        <button type="button" class="btn btn-danger" id="resetButton"> 
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Activity Type</th>
                                            <th>User</th>
                                            <th>Content</th>
                                            <th>Description</th>
                                            <th>IP Address</th>
                                            <th>Datetime</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @include('backend.admin.activityLog.table')
                                    </tbody>
                                </table>
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
        $('#type, #from_date, #to_date').on('change keyup', function () {
            fetchFilteredData();
        });

        $('#resetButton').on('click', function () {
            $('#type').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            
            fetchFilteredData();
        });

        function fetchFilteredData() {
            const type = $('#type').val();
            const from_date = $('#from_date').val();
            const to_date = $('#to_date').val();

            $.ajax({
                url: "{{ route('admin.report.logReport') }}",
                type: "GET",
                data: {
                    type: type,
                    from_date: from_date,
                    to_date: to_date,
                },
                beforeSend: function () {
                    $('#datatable tbody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.html != '') {
                            $('#datatable tbody').html(response.html);
                        } else {
                            $('#datatable tbody').html('<tr><td colspan="7" class="text-center">No data found</td></tr>');
                        }
                    } else {
                        $('#datatable tbody').html('<tr><td colspan="7" class="text-center">No data found</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#datatable tbody').html('<tr><td colspan="7" class="text-center text-danger">An error occurred</td></tr>');
                },
            });
        }
    </script>
@endpush