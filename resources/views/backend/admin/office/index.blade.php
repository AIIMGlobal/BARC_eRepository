@extends('backend.layouts.app')

@section('title', 'Organization List | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">Organization List</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Organization List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Organization List</h4>

                            <div class="flex-shrink-0">
                                @can('add_office')
                                    <a class="btn btn-primary" href="{{ route('admin.office.create') }}">
                                        Add New Organization
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="office_id" id="office_id" class="form-control select2">
                                                <option value="">--Search by Office--</option>

                                                @if (count($orgs) > 0)
                                                    @foreach ($orgs as $org)
                                                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
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
                                            <th class="text-center">Organization Logo</th>
                                            <th>Organization Name</th>
                                            <th>Division</th>
                                            {{-- <th>District</th>
                                            <th>Upazila</th> --}}
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @include('backend.admin.office.table')
                                    </tbody>
                                    <!-- end tbody -->
                                </table>
                                <!-- end table -->
                            </div>
                            <!-- end table responsive -->
                        </div>
                        <!-- end card body -->

                        {{-- <div class="card-footer">
                            {{ $offices->links()}}
                        </div> --}}
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
        $('#office_id').on('change keyup', function () {
            fetchFilteredData();
        });

        $('#resetButton').on('click', function () {
            $('#office_id').val('').trigger('change');

            fetchFilteredData();
        });

        function fetchFilteredData() {
            const office_id = $('#office_id').val();

            $.ajax({
                url: "{{ route('admin.office.index') }}",
                type: "GET",
                data: {
                    office_id: office_id,
                },
                beforeSend: function () {
                    $('#datatable tbody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.html != '') {
                            $('#datatable tbody').html(response.html);
                        } else{
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

    <script>
        $(document).on('click', '.destroy', function(e) {
            e.preventDefault();
            
            let officeId = $(this).data('id');
            let deleteUrl = "{{ route('admin.office.delete', ':id') }}".replace(':id', officeId);

            Swal.fire({
                title: "Are you sure want to delete?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.message, "success")
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush