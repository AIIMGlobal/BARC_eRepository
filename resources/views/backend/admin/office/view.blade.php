@extends('backend.layouts.app')

@section('title', 'Organization Details | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">Organization Details</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Organization Details</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Organization Details</h4>

                            @can('manage_office')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.office.index') }}" class="btn btn-primary">Organization List</a>
                                </div>
                            @endcan
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="name" class="form-label">Organization Name:</label>

                                        <input id="name" type="text" class="form-control" name="name" placeholder="Enter Organization Name" value="{{ $office->name ?? old('name') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="short_name" class="form-label">Organization Short Name: </label>

                                        <input id="short_name" type="text" class="form-control" name="short_name" placeholder="Enter Organization Short Name" value="{{ $office->short_name ?? old('short_name') }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <label for="division" class="form-label">Division:</label>

                                    <input id="division" type="text" class="form-control" placeholder="Enter Division Name" value="{{ $office->division->name_en ?? '-' }}" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="district" class="form-label">District: </label>

                                    <input id="district" type="text" class="form-control" placeholder="Enter District Name" value="{{ $office->district->name_en ?? '-' }}" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="upazila" class="form-label">Upazila/Thana: </label>

                                    <input id="upazila" type="text" class="form-control" placeholder="Enter Upazila Name" value="{{ $office->upazila->name_en ?? '-' }}" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="website_url" class="form-label">Website URL: </label>

                                        <textarea name="website_url" id="website_url" class="form-control" cols="30" rows="1" placeholder="Enter Website URL" disabled>{{ $office->website_url ?? old('website_url') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 col-sm-12">
                                    <div>
                                        <label for="created_by" class="form-label">Created By: </label>

                                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ $office->createdBy->name_en ?? '' }}" disabled>
                                    </div>
                                </div>

                                @if ($office->updated_by)
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="updated_by" class="form-label">Updated By: </label>

                                            <input type="text" class="form-control" id="updated_by" name="updated_by" value="{{ $office->updatedBy->name_en ?? '' }}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-sm-12 mt-3">
                                    <div>
                                        <label for="created_at" class="form-label">Created At: </label>

                                        <input type="text" class="form-control" id="created_at" name="created_at" value="{{ date('d M, Y', strtotime($office->created_at)) }} at {{ date('h:i a', strtotime($office->created_at)) }}" disabled>
                                    </div>
                                </div>

                                @if ($office->updated_by)
                                    <div class="col-md-6 col-sm-12 mt-3">
                                        <div>
                                            <label for="updated_at" class="form-label">Updated At: </label>

                                            <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ date('d M, Y', strtotime($office->updated_at)) }} at {{ date('h:i a', strtotime($office->updated_at)) }}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 col-sm-12 mt-2">
                                    <div>
                                        <label for="logo" class="">Organization logo: </label>

                                        @if ($office->logo)
                                            <img src="{{ asset('storage/' . $office->logo) }}" alt="Organization logo" style="max-height: 95px;">
                                        @else
                                            <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Organization logo" style="max-height: 95px;">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12 mt-4">
                                    <div>
                                        <label for="status" class="">Status: </label>

                                        @if ($office->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($office->status == 0)
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
        $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush
