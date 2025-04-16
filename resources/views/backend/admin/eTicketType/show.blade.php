@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View E-Ticket Type')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View E-Ticket Type</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">View E-Ticket Type</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View E-Ticket Type</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="title" class="form-label">E-Ticket Type: </label>

                                        <input type="text" class="form-control" id="title" name="title" value="{{ $type->title }}" disabled>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="description" class="form-label">Description: </label>

                                        <textarea class="form-control" name="description" id="description" cols="30" rows="5" disabled>{{ $type->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="sl" class="form-label">Order No: </label>

                                        <input type="text" class="form-control" id="sl" name="sl" value="{{ $type->sl }}" disabled>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="created_by" class="form-label">Created By: </label>

                                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ $type->createdBy->name_en ?? '' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            @if ($type->updated_by)
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="updated_by" class="form-label">Updated By: </label>

                                            <input type="text" class="form-control" id="updated_by" name="updated_by" value="{{ $type->updatedBy->name_en ?? '' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="created_at" class="form-label">Created At: </label>

                                        <input type="text" class="form-control" id="created_at" name="created_at" value="{{ $type->created_at ?? '' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            @if ($type->updated_by)
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="updated_at" class="form-label">Updated At: </label>

                                            <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ $type->updated_at ?? '' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                        <input disabled autocomplete="off" class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" @if($type->status == 1) checked @endif value="1">
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