@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View Leave Category')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View Leave Category</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">View Leave Category</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View Leave Category</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div>
                                        <label for="name_en" class="form-label">Leave Category: </label>

                                        <input type="text" class="form-control" id="name_en" name="name_en" value="{{ $category->name_en }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-12">
                                    <div>
                                        <label for="day_number" class="form-label">Maximum Countable Day: </label>

                                        <input type="text" class="form-control" id="day_number" name="day_number" value="{{ $category->day_number }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-12">
                                    <div>
                                        <label for="created_by" class="form-label">Created By: </label>

                                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ $category->createdBy->name_en ?? '' }}" disabled>
                                    </div>
                                </div>

                                @if ($category->updated_by)
                                    <div class="col-md-3 col-sm-12">
                                        <div>
                                            <label for="updated_by" class="form-label">Updated By: </label>

                                            <input type="text" class="form-control" id="updated_by" name="updated_by" value="{{ $category->updatedBy->name_en ?? '' }}" disabled>
                                        </div>
                                    </div>
                                @endif
                            </div>
                                
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                        <input disabled autocomplete="off" class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" @if($category->status == 1) checked @endif value="1">
                                        <label class="form-check-label" for="statusOption">Status</label>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
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
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush