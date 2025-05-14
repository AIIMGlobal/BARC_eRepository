@extends('backend.layouts.app')

@section('title', 'Edit Content | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item"><a href="{{ route('admin.content.index') }}">Content List</a></li>

                                <li class="breadcrumb-item active" aria-current="page">Edit Content</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Edit Content</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form id="editForm" action="{{ route('admin.content.update', Crypt::encryptString($content->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" id="contentStatus" name="status">

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="content_name" class="form-label fw-bold">Content Name: <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" id="content_name" name="content_name" placeholder="Enter content name" value="{{ old('content_name', $content->content_name) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label fw-bold">Category: <span class="text-danger">*</span></label>

                                        <select name="category_id" class="form-control select2" id="category_id" required>
                                            <option value="">--Select Category--</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ $content->category_id == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content_year" class="form-label fw-bold">Content Year: <span class="text-danger">*</span></label>

                                        <input type="number" class="form-control" id="content_year" name="content_year" placeholder="Enter year (e.g., 2023)" value="{{ old('content_year', $content->content_year) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content_type" class="form-label fw-bold">Content Type: <span class="text-danger">*</span></label>

                                        <select name="content_type" class="form-control select2" id="content_type" required>
                                            <option value="">--Select Content Type--</option>

                                            <option value="Video" {{ $content->content_type == 'Video' ? 'selected' : '' }}>Video</option>
                                            <option value="PDF" {{ $content->content_type == 'PDF' ? 'selected' : '' }}>PDF</option>
                                            <option value="Audio" {{ $content->content_type == 'Audio' ? 'selected' : '' }}>Audio</option>
                                            <option value="Image" {{ $content->content_type == 'Image' ? 'selected' : '' }}>Image</option>
                                            <option value="Other" {{ $content->content_type == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="meta_description" class="form-label fw-bold">Description</label>

                                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content" class="form-label fw-bold">
                                            Content File:

                                            @if ($content->content)
                                                <a href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}" class="btn btn-info btn-sm mt-2" style="margin-top: 0 !important;" target="_blank">View Content</a>
                                            @endif
                                        </label>

                                        <input type="file" class="form-control" id="content" name="content">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="thumbnail" class="form-label fw-bold">Thumbnail:</label>

                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">

                                        <div id="thumbnail-preview" class="mt-2">
                                            <img id="thumbnail-image" src="{{ $content->thumbnail ? asset('storage/' . $content->thumbnail) : asset('images/dummy-thumbnail.jpg') }}" alt="Thumbnail Preview" class="img-fluid rounded" style="max-width: 200px;">
                                        </div>
                                    </div>
                                </div>

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
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
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
                        $('#thumbnail-image').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#thumbnail-image').attr('src', '{{ $content->thumbnail ? asset('storage/' . $content->thumbnail) : asset('images/dummy-thumbnail.jpg') }}');
                }
            });

            $('#editForm').on('submit', function(e) {
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

                            if (status == 0) {
                                setTimeout(() => window.location.href = '{{ route('admin.content.indexMyContent') }}', 1000);
                            } else {
                                setTimeout(() => window.location.href = '{{ route('admin.content.index') }}', 1000);
                            }
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