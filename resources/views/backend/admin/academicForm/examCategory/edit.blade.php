@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Update Exam Category'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Update Exam Category')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Update Exam Category')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Update Exam Category')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="{{route('admin.examCategory.update', $examCategory->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="name_en" class="form-label">{{__('pages.Exam Category Name')}}<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="name_en" id="name_en" value="{{$examCategory->name_en}}" required>
                                    </div>
                                </div>

                                @php
                                    $explodedExams = explode(",", $examCategory->exam_ids);
                                @endphp

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="exam_ids" class="form-label">{{__('pages.Select Exams')}}<span style="color:red;">*</span></label>
                                        <select class="form-control select2" name="exam_ids[]" id="exam_ids" multiple required>
                                            <option value="">--{{__('pages.Select Exams')}}--</option>
                                            @foreach ($exams as $exam)
                                                <option value="{{$exam->id}}" {{ (in_array($exam->id, $explodedExams)) ? 'selected' : '' }}>{{$exam->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" @if($examCategory->status == 1) checked @endif>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{__('pages.Update')}}</button>
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
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>


@endpush