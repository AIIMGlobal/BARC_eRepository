@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | বার্তা দেখুন')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">বার্তা দেখুন</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">বার্তা দেখুন</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">বার্তা দেখুন</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div>
                                    <label for="নাম" class="form-label">শিরোনাম:</label>
                                    <div class="w-100">
                                        {{$message->title}}
                                    </div>
                                </div>
                                <div>
                                    <br>
                                    <label for="" class="form-label">বর্ণনা</label>
                                    <div class="w-100">
                                        {{$message->description}}
                                    </div>
                                </div>
                                <div>
                                    <br>
                                    <label for="" class="form-label">ফাইল:</label>
                                    @if ($message->image)

                                        <a href="{{asset('storage/message')}}/{{$message->image}}" download style="font-size: 25px;">
                                            <i class="ri-download-cloud-2-fill"></i>

                                        </a>
                                    @else
                                    কোনো ফাইল যোগ করা হয়নি
                                    @endif
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->

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
@push('css')
<style>
    .table-area tbody tr:first-child .remove_more {
        display: none;
    }

</style>
@endpush
@push('script')
<script>
    $('[href*="{{$menu_expand}}"]').addClass('active');
    $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
    $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
    $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
    $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');

</script>

{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
{{-- <script src="{{asset('backend-assets/assets/js/pages/notifications.init.js')}}"></script> --}}
<script>
    // $(document).ready(function () {
    //     $('.select2').select2();
    // });

    $('#myTab li').click(function (e) {
    // e.preventDefault();
        $(this).find('a').tab('show');
    // $(this).tab('show');
        $(this).closest('ul').find('input[type="checkbox"]').prop('checked','');
        $(this).closest('li').find('input[type="checkbox"]').prop('checked','checked');

    });

</script>
@endpush

@push('css')

@endpush
