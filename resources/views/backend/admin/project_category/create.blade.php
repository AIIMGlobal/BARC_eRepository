@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | Add project category')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add project category</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">Add project category</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

            <div class="col-xxl-12">

                @include('backend.admin.partials.alert')

                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add project category</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="{{route('admin.project_category.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
        
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="name" class="form-label">Category Name <span style="color:red;">*</span></label>
                                        <input id="name" type="text" class="form-control" name="name" placeholder="Category Name" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    
                                </div>
                                
                                
                                <div class="col-md-4 col-sm-12 mt-4">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" checked value="1">
                                        <label class="form-check-label" for="statusOption">{{__('pages.Status')}}</label>
                                    </div>
                                </div><!--end col-->
                                <div>
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{__('pages.Submit')}}</button>
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
        $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush