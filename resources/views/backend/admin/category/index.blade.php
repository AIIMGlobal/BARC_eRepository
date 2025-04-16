@extends('backend.layouts.app')

@section('title', 'Category List | '.($global_setting->title ?? ""))

@push('css')
    <style>
        .submenu {
            display: none;
        }
        .submenu.active {
            display: block;
        }

        @media (min-width: 768px) {
            .submenu {
                display: none;
                position: absolute;
                background: white;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 10;
            }
            .menu-item:hover > .submenu {
                display: block;
            }
        }
    </style>
@endpush

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

                                <li class="breadcrumb-item active">Category List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Category List</h4>

                            <div class="flex-shrink-0">
                                @can('add_office')
                                    <a class="btn btn-primary" href="{{ route('admin.category.create') }}">
                                        Add New Category
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
                                            
                                        </div>
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
                                            <th>Category Name</th>
                                            <th>Parent Category Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @include('backend.admin.category.table')
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
                url: "{{ route('admin.category.index') }}",
                type: "GET",
                data: {
                    office_id: office_id,
                },
                beforeSend: function () {
                    $('#datatable tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.html != '') {
                            $('#datatable tbody').html(response.html);
                        } else{
                            $('#datatable tbody').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                        }
                    } else {
                        $('#datatable tbody').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#datatable tbody').html('<tr><td colspan="5" class="text-center text-danger">An error occurred</td></tr>');
                },
            });
        }
    </script>

    <script>
        $(document).on('click', '.destroy', function(e) {
            e.preventDefault();
            
            let officeId = $(this).data('id');
            let deleteUrl = "{{ route('admin.category.delete', ':id') }}".replace(':id', officeId);

            Swal.fire({
                title: "Are you sure want to delete?",
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