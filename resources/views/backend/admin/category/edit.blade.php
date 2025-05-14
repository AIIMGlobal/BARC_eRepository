@extends('backend.layouts.app')

@section('title', 'Edit Category | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Edit Category</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit Category</h4>

                            @can('category_list')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Category List</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <form id="updateForm" action="{{ route('admin.category.update', Crypt::encryptString($category->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="category_name" class="form-label">Category Name: <span style="color:red;">*</span></label>

                                            <input id="category_name" type="text" class="form-control" name="category_name" placeholder="Enter Category Name" value="{{ $category->category_name ?? old('category_name') }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <label for="parent_id" class="form-label">Parent Category: </label>

                                        <select name="parent_id" class="form-control select2" id="parent_id">
                                            <option value="">--Select Parent Category--</option>

                                            @foreach ($categorys as $parent)
                                                <option value="{{ $parent->id }}" {{ $parent->id == $category->parent_id ? 'selected' : '' }}>{{ $parent->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="description" class="form-label">Description: </label>

                                            <textarea name="description" id="description" class="form-control" cols="30" rows="1" placeholder="Enter Description">{{ $category->description ?? old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="image" class="form-label">Upload New Category Image:</label>

                                            <input type="file" class="form-control" id="image" name="image">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <label for="image" class="">Current Category Image:</label>

                                            @if ($category->image && Storage::exists('public/' . $category->image))
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" style="max-height: 95px;">
                                            @else
                                                <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Category Image" style="max-height: 95px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div class="switchery-demo">
                                            <input type="checkbox" name="status" class="js-switch" value="1" {{ $category->status == 1 ? 'checked' : '' }}> Status
                                        </div>
                                    </div>

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-success" id="submitBtn">Update</button>
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
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
    
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
                        if (response.success) {
                            Swal.fire({
                                title: response.message,
                                icon: 'success',
                                showCancelButton: false,
                            });

                            form.trigger('reset');

                            if ($('.select2').length > 0) {
                                $('.select2').val('').trigger('change');
                            }

                            setTimeout(() => window.location.reload(), 1000);

                            $('#submitBtn').prop('disabled', false);
                            $('#submitBtn').html(`Update`);
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                showCancelButton: false,
                            });
                        }
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: "Something went wrong. Please try again.",
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush