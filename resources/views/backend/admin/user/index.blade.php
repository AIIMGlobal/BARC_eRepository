@extends('backend.layouts.app')

@section('title', 'User List | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">User List</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">User List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">User List</h4>

                            <div class="flex-shrink-0">
                                @can('archive_list')
                                    <a href="{{ route('admin.user.archive_list') }}" class="btn btn-warning">Archive List</a>
                                @endcan

                                @can('add_user')
                                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Add New User</a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-3 col-sm-6 col-12 pt-1">
                                        <div class="search-box">
                                            <div>
                                                <select class="form-control select2" name="user_id" id="user_id">
                                                    <option value="">--Search by User--</option>

                                                    @foreach ($users as $user)
                                                        <option @if (isset($_GET['user_id']) && $_GET['user_id'] == $user->id) selected @endif
                                                        value="{{ $user->id }}">{{ $user->name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-4 col-6 pt-1">
                                        <div class="d-flex flex-row bd-highlight mb-0">
                                            <div class="pl-1 bd-highlight">
                                                <a id="resetButton" style="max-width: 150px; border-radius: 5px" href="javascript:void(0)" class="btn btn-danger">
                                                    <i class="ri-restart-line align-bottom"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Mobile</th>
                                            <th>User Type</th>
                                            <th>Role</th>
                                            <th>Designation</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @include('backend.admin.user.table')
                                    </tbody>
                                    <!-- end tbody -->
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
        $('#searchText, #user_id').on('change keyup', function () {
            fetchFilteredData();
        });

        $('#resetButton').on('click', function () {
            // $('#searchText').val('');
            $('#user_id').val('').trigger('change');

            fetchFilteredData();
        });

        function fetchFilteredData() {
            // const searchText = $('#searchText').val();
            const user_id = $('#user_id').val();

            $.ajax({
                url: "{{ route('admin.user.index') }}",
                type: "GET",
                data: {
                    // searchText: searchText,
                    user_id: user_id,
                },
                beforeSend: function () {
                    $('#datatable tbody').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.html != '') {
                            $('#datatable tbody').html(response.html);
                        } else{
                            $('#datatable tbody').html('<tr><td colspan="8" class="text-center">No data found</td></tr>');
                        }
                    } else {
                        $('#datatable tbody').html('<tr><td colspan="8" class="text-center">No data found</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#datatable tbody').html('<tr><td colspan="8" class="text-center text-danger">An error occurred</td></tr>');
                },
            });
        }

        // fetchFilteredData();
    </script>

    <script>
        function archive(Id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to archive this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Archive'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.block') }}",
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: Id
                        },
                        beforeSend: function() {
                            $('.btn-danger').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });

                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', xhr.responseJSON.message || 'An error occurred.', 'error');
                        },
                        complete: function() {
                            $('.btn-danger').prop('disabled', false);
                        }
                    });
                }
            });
        }

        function destroy(Id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff0000',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.delete') }}",
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: Id
                        },
                        beforeSend: function() {
                            $('.btn-danger').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });

                                // $('tr').filter('[data-id="' + Id + '"]').remove();
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', xhr.responseJSON.message || 'An error occurred.', 'error');
                        },
                        complete: function() {
                            $('.btn-danger').prop('disabled', false);
                        }
                    });
                }
            });
        }
    </script>
@endpush