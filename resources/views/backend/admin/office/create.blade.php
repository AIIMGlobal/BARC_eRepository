@extends('backend.layouts.app')

@section('title', 'Add New Organization | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">Add New Organization</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Add New Organization</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Add New Organization</h4>

                            @can('manage_office')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.office.index') }}" class="btn btn-primary">Organization List</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <form id="createForm" action="{{ route('admin.office.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Organization Name: <span style="color:red;">*</span></label>

                                            <input id="name" type="text" class="form-control" name="name" placeholder="Enter Organization Name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="short_name" class="form-label">Organization Short Name: </label>

                                            <input id="short_name" type="text" class="form-control" name="short_name" placeholder="Enter Organization Short Name" value="{{ old('short_name') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="division" class="form-label">Division: <span style="color:red;">*</span></label>

                                        <select name="division" class="form-control division_id_0 select2" id="present_division_id" required>
                                            <option value="">--Select Division--</option>

                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="district" class="form-label">District: </label>

                                        <select name="district" class="form-control district_id_0 select2" id="present_district_id">
                                            <option value="">--Select Division First--</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="upazila" class="form-label">Upazila/Thana: </label>

                                        <select name="upazila" class="form-control upazila_id_0 select2" id="present_upazila_id">
                                            <option value="">--Select District First--</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="website_url" class="form-label">Website URL: </label>

                                            <textarea name="website_url" id="website_url" class="form-control" cols="30" rows="1" placeholder="Enter Website URL">{{ old('website_url') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="logo" class="form-label">Organization Logo: </label>

                                            <input id="logo" type="file" class="form-control" name="logo">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="switchery-demo">
                                            <input type="checkbox" name="status" class="js-switch" value="1" checked> Status
                                        </div>
                                    </div>

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                        </div>
                                    </div>
                                </div><!--end row-->
                            </form>
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
        $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    @include('backend.admin.partials.addressDynamic')
    
    <script>
        $(document).ready(function() {
            $('#createForm').on('submit', function(e) {
                e.preventDefault();

                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading...`);

                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("button[type='submit']").prop("disabled", true);
                    },
                    success: function(response) {
                        Swal.fire({
                            title: response.message,
                            icon: 'success',
                            showCancelButton: false,
                        });

                        form.trigger('reset');

                        if ($('.select2').length > 0) {
                            $('.select2').val('').trigger('change');
                        }

                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtn').html(`Submit`);
                    },
                    error: function(xhr) {
                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtn').html(`Submit`);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = "";

                            $.each(errors, function(key, value) {
                                errorMessages += value[0] + "\n";
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error!',
                                text: errorMessages,
                            });
                        } else {
                            toastr.options.closeButton = true;
                            toastr.options.timeOut = 1500;
                            toastr.error("Something went wrong. Please try again.");
                        }
                    }
                });
            });
        });
    </script>
@endpush