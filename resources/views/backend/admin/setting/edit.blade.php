@extends('backend.layouts.app')

@section('title', 'Settings | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Settings</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xxl-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Settings</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <form id="save_training" action="{{ route('admin.setting.update', $setting->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-3"></div>

                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div>
                                                    <label for="title" class="form-label">Title: <span style="color:red;">*</span></label>

                                                    <input type="text" class="form-control" name="title" placeholder="Enter Title" value="{{ $setting->title ?? '' }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div>
                                                    <label for="sub_title" class="form-label">Sub-title: <span style="color:red;">*</span></label>

                                                    <input type="text" class="form-control" name="sub_title" placeholder="Enter Sub-title" value="{{ $setting->sub_title ?? '' }}" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div>
                                                    <label for="email" class="form-label">Email: <span style="color:red;">*</span></label>

                                                    <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $setting->email ?? '' }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div>
                                                    <label for="mobile" class="form-label">Phone: <span style="color:red;">*</span></label>

                                                    <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile" value="{{ $setting->mobile ?? '' }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div>
                                                    <label for="address" class="form-label">Address: <span style="color:red;">*</span></label>

                                                    <textarea class="form-control" name="address" placeholder="Enter Address" rows="3" cols="1" required>{{ $setting->address ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div>
                                                    <label for="social_link" class="form-label">Location in Map: </label>

                                                    <textarea class="form-control" name="social_link" placeholder="Enter Embedded Code of Map" rows="3" cols="1">{{ $setting->social_link ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            @if (Auth::user()->role_id == 1)
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="copyright" class="form-label">Copyright Text: </label>

                                                        <textarea class="form-control" name="copyright" placeholder="Enter Copyright Text" rows="1" cols="1">{{ $setting->copyright ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <div>
                                                    <label for="soft_logo" class="form-label">Software Logo: </label>

                                                    <input type="file" class="form-control" name="soft_logo" data-allow-reorder="true">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-4">
                                                <img style="max-height: 60px; max-width:150px;" class="img-thumbnail" src="{{ asset('storage/soft_logo') }}/{{ $setting->soft_logo ?? '' }}" alt="Logo">
                                            </div>

                                            @if (Auth::user()->role_id == 1)
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="logo" class="form-label">Logo: </label>

                                                        <input type="file" class="form-control" name="logo" data-allow-reorder="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mt-4">
                                                    <img style="max-height: 60px; max-width:150px;" class="img-thumbnail" src="{{ asset('storage/logo/' . ($global_setting->logo ?? '')) }}" alt="Logo">
                                                </div>
                                            @endif

                                            <div class="col-md-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
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
    </div>
    <!-- container-fluid -->
@endsection