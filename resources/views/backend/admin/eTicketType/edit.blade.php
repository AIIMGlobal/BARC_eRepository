@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Edit E-Ticket Type')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit E-Ticket Type</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Edit E-Ticket Type</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit E-Ticket Type</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.eTicketType.update', Crypt::encryptString($type->id)) }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="title" class="form-label">E-Ticket Type<span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter E-Ticket Type" value="{{ $type->title ?? old('title') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="description" class="form-label">Description</label>

                                            <textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="5">{{ $type->description ?? old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="sl" class="form-label">Sort Order No</label>

                                            <input type="number" min="1" class="form-control" id="sl" name="sl" placeholder="Enter Sort Order No." value="{{ $type->sl ?? old('sl') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success">
                                            <input autocomplete="off" class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" @if($type->status == 1) checked @endif value="1">
                                            <label class="form-check-label" for="statusOption">Status</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div><!--end col-->
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
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush