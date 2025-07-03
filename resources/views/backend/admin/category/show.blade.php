@extends('backend.layouts.app')

@section('title', 'Category Details | '.($global_setting->title ?? ""))

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

                                <li class="breadcrumb-item active">Category Details</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Category Details</h4>

                            @can('category_list')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Category List</a>
                                </div>
                            @endcan
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="category_name" class="form-label">Category Name:</label>

                                        <input id="category_name" type="text" class="form-control" name="category_name" placeholder="Enter Category Name" value="{{ $category->category_name ?? old('category_name') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <label for="parent" class="form-label">Parent Category Name:</label>

                                    <input id="parent" type="text" class="form-control" placeholder="Enter Parent Category Name" value="{{ $category->parent->category_name ?? 'Parent Category' }}" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="description" class="form-label">Description: </label>

                                        <textarea name="description" id="description" class="form-control" cols="30" rows="1" placeholder="Enter Description" disabled>{{ $category->description ?? old('description') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12 mt-2">
                                    <div>
                                        <label for="logo" class="">Category logo: </label>

                                        @if ($category->image && Storage::exists('public/' . $category->image))
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" style="max-height: 95px;">
                                        @else
                                            <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Category Image" style="max-height: 95px;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="created_by" class="form-label">Created By: </label>

                                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ $category->createdBy->name_en ?? '' }}" disabled>
                                    </div>
                                </div>

                                @if ($category->updated_by)
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="updated_by" class="form-label">Updated By: </label>

                                            <input type="text" class="form-control" id="updated_by" name="updated_by" value="{{ $category->updatedBy->name_en ?? '' }}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="created_at" class="form-label">Created on: </label>

                                        <input type="text" class="form-control" id="created_at" name="created_at" value="{{ date('d M, Y', strtotime($category->created_at)) }} at {{ date('h:i a', strtotime($category->created_at)) }}" disabled>
                                    </div>
                                </div>

                                @if ($category->updated_by)
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="updated_at" class="form-label">Updated on: </label>

                                            <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ date('d M, Y', strtotime($category->updated_at)) }} at {{ date('h:i a', strtotime($category->updated_at)) }}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="status" class="">Status: </label>

                                        @if ($category->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($category->status == 0)
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
@endpush
