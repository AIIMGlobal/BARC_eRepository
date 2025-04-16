@extends('backend.layouts.app')

@section('title', 'Edit Organization Information | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">Edit Organization Information</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Edit Organization Information</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit Organization Information</h4>

                            @can('manage_office')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.office.index') }}" class="btn btn-primary">Organization List</a>
                                </div>
                            @endcan
                        </div>
                        
                        <div class="card-body">
                            <form id="updateForm" action="{{route('admin.office.update', Crypt::encryptString($office->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Organization Name: <span style="color:red;">*</span></label>

                                            <input id="name" type="text" class="form-control" name="name" placeholder="Enter Organization Name" value="{{ $office->name ?? old('name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="short_name" class="form-label">Organization Short Name: </label>

                                            <input id="short_name" type="text" class="form-control" name="short_name" placeholder="Enter Organization Short Name" value="{{ $office->short_name ?? old('short_name') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="division" class="form-label">Division: <span style="color:red;">*</span></label>

                                        <select name="division" class="form-control division_id_0 select2" id="present_division_id" required>
                                            <option value="">--Select Division--</option>

                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ $division->id == $office->division_id ? 'selected' : '' }}>{{ $division->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="district" class="form-label">District: </label>

                                        <select name="district" class="form-control district_id_0 select2" id="present_district_id">
                                            <option value="">--Select District--</option>

                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" {{ $district->id == $office->district_id ? 'selected' : '' }}>{{ $district->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="upazila" class="form-label">Upazila/Thana: </label>

                                        <select name="upazila" class="form-control upazila_id_0 select2" id="present_upazila_id">
                                            @if ($office->district_id)
                                                <option value="">--Select Upazila/Thana--</option>

                                                @foreach ($upazilas as $upazila)
                                                    <option value="{{ $upazila->id }}" {{ $upazila->id == $office->upazila_id ? 'selected' : '' }}>{{ $upazila->name_en }}</option>
                                                @endforeach
                                            @else
                                                <option value="">--Select District First--</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="website_url" class="form-label">Website URL: </label>

                                            <textarea name="website_url" id="website_url" class="form-control" cols="30" rows="1" placeholder="Enter Website URL">{{ $office->website_url ?? old('website_url') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="logo" class="form-label">Upload New Organization Logo:</label>

                                            <input type="file" class="form-control" id="logo" name="logo">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div>
                                            <label for="logo" class="">Current Organization Logo:</label>

                                            @if ($office->logo)
                                                <img src="{{ asset('storage/' . $office->logo) }}" alt="Organization Logo" style="max-height: 95px;">
                                            @else
                                                <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Organization Logo" style="max-height: 95px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="switchery-demo">
                                            <input type="checkbox" name="status" class="js-switch" value="1" {{ $office->status == 1 ? 'checked' : '' }}> Status
                                        </div>
                                    </div>

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
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
            $('#updateForm').on('submit', function(e) {
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

                        // form.trigger('reset');

                        // if ($('.select2').length > 0) {
                        //     $('.select2').val('').trigger('change');
                        // }

                        setTimeout(() => window.location.reload(), 1000);

                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtn').html(`Update`);
                    },
                    error: function(xhr) {
                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtn').html(`Update`);

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