@extends('backend.layouts.app')

@section('title', 'Create New Content | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active" aria-current="page">Create Content</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Create New Content</h4>

                            @can('content_list')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.content.index') }}" class="btn btn-primary">Content List</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <form id="createForm" action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" id="contentStatus" name="status">

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="content_name" class="form-label fw-bold">Content Name: <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="content_name" name="content_name" placeholder="Enter content name" value="{{ old('content_name') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label fw-bold">Category: <span class="text-danger">*</span></label>

                                        <select name="category_id" class="form-control select2" id="category_id" required>
                                            <option value="">--Select Category--</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content_year" class="form-label fw-bold">Content Year: <span class="text-danger">*</span></label>

                                        <input type="number" class="form-control" id="content_year" name="content_year" placeholder="Enter year (e.g., 2023)" value="{{ old('content_year') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content_type" class="form-label fw-bold">Content Type: <span class="text-danger">*</span></label>

                                        <select name="content_type" class="form-control select2" id="content_type" required>
                                            <option value="">--Select Content Type--</option>

                                            <option value="Video">Video</option>
                                            <option value="PDF">PDF</option>
                                            <option value="Article">Article</option>
                                            <option value="Audio">Audio</option>
                                            <option value="Image">Image</option>
                                        </select>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <label for="extension" class="form-label fw-bold">Extension</label>

                                        <input type="text" class="form-control" id="extension" name="extension" placeholder="Enter extension (e.g., pdf, mp4)" value="{{ old('extension') }}">
                                    </div> --}}

                                    {{-- <div class="col-md-6">
                                        <label for="sl" class="form-label fw-bold">Serial Number</label>

                                        <input type="text" class="form-control" id="sl" name="sl" placeholder="Enter serial number" value="{{ old('sl') }}">
                                    </div> --}}

                                    <div class="col-md-6">
                                        <label for="content" class="form-label fw-bold">Content File: <span class="text-danger">*</span></label>

                                        <input type="file" class="form-control" id="content" name="content" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="thumbnail" class="form-label fw-bold">Thumbnail:</label>

                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">

                                        <div id="thumbnail-preview" class="mt-2">
                                            <img id="thumbnail-image" src="#" alt="Thumbnail Preview" class="img-fluid rounded" style="max-width: 200px; display: none;">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <label for="meta_title" class="form-label fw-bold">Meta Title</label>

                                        <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter meta title" value="{{ old('meta_title') }}">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="meta_description" class="form-label fw-bold">Meta Description</label>

                                        <textarea class="form-control" id="meta_description" name="meta_description" rows="4" placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="meta_keywords" class="form-label fw-bold">Meta Keywords</label>

                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Enter meta keywords (comma-separated)" value="{{ old('meta_keywords') }}">
                                    </div> --}}
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <button type="submit" class="btn btn-success" id="saveBtn" data-status="0">Save</button>

                                    <button type="submit" class="btn btn-primary" id="publishBtn" data-status="1">Publish</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');

        $(document).ready(function() {
            $('#thumbnail').on('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('#thumbnail-image').attr('src', e.target.result).show();
                    };

                    reader.readAsDataURL(file);
                } else {
                    $('#thumbnail-image').hide();
                }
            });

            $('#createForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $(document.activeElement);
                const status = submitBtn.data('status');

                $('#contentStatus').val(status);

                const form = $(this);
                const formData = new FormData(this);
                const btnText = submitBtn.text();

                submitBtn.prop('disabled', true);
                submitBtn.html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ${btnText}ing...`);

                $.ajax({
                    url: form.attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("button[type='submit']").prop("disabled", true);
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: response.message,
                                icon: 'success',
                                showCancelButton: false,
                            });

                            // form.trigger('reset');

                            // $('#thumbnail-image').hide();

                            // if ($('.select2').length > 0) {
                            //     $('.select2').val('').trigger('change');
                            // }

                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                showCancelButton: false,
                            });
                        }

                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);

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
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: "An unexpected error occurred. Please try again.",
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush