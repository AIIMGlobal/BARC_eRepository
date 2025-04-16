@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.View Duration Details'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.View Duration Details')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.View Duration Details')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.View Duration Details')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="name_en" class="form-label">{{__('pages.Duration Name')}}</label>
                                        <input type="text" class="form-control" name="name_en" id="name_en" value="{{$duration->name_en}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="created_by" class="form-label">{{__('pages.Created By')}}</label>
                                        <input type="text" class="form-control" name="created_by" id="created_by" value="{{$duration->createdBy->name_en ?? '-'}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="created_at" class="form-label">{{__('pages.Created At')}}</label>
                                        <input type="text" class="form-control" name="created_at" id="created_at" value="{{date('d-m-Y', strtotime($duration->created_at))}}" disabled>
                                    </div>
                                </div>

                                @if(($duration->updated_by != '') && ($duration->updated_by != NULL))
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="updated_by" class="form-label">{{__('pages.Updated By')}}</label>
                                            <input type="text" class="form-control" name="updated_by" id="updated_by" value="{{$duration->updatedBy->name_en ?? '-'}}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="updated_at" class="form-label">{{__('pages.Updated At')}}</label>
                                            <input type="text" class="form-control" name="updated_at" id="updated_at" value="{{date('d-m-Y', strtotime($duration->updated_at))}}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" @if($duration->status == 1) checked @endif disabled>
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
        $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush