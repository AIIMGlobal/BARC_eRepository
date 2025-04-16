@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Add New Educational Form'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Add New Educational Form')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Add New Educational Form')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Add New Educational Form')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="{{route('admin.education_form.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">

                                

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="name_en" class="form-label">{{__('pages.Educational Form Name')}}<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="name_en" id="name_en" placeholder="Enter Educational Form Name" autocomplete="off" value="{{old('name_en')}}" required>
                                    </div>
                                </div>
                                
                                

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="roll">{{__('pages.Roll')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="roll" id="roll" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="reg_no">{{__('pages.Show Registration input field')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="reg_no" id="reg_no" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <label for="institute_ids" class="form-label">{{__("pages.Select Institute (keep blank if don't want to show)")}}</label>
                                        <select class="form-control select2" name="institute_ids[]" id="institute_ids" multiple>
                                            @foreach ($institutes as $institute)
                                                <option value="{{$institute->id}}">{{$institute->name_en}}</option>
                                            @endforeach
                                        </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="institute_name">{{__('pages.Show Institute Input Field')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="institute_name" id="institute_name" value="1" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="pass_year">{{__('pages.Show Passing Year')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="pass_year" id="pass_year" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <label for="exam_category_id" class="form-label">{{__("pages.Select Exam Category (keep blank if don't want to show)")}}</label>
                                        <select class="form-control select2" name="exam_category_id" id="exam_category_id">
                                            <option value="">--{{__('pages.Select Exam Category')}}--</option>
                                            @foreach ($exam_categories as $exam_category)
                                                <option value="{{$exam_category->id}}">{{$exam_category->name_en}}</option>
                                            @endforeach
                                        </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="exam_name">{{__('pages.Show Exam Input Field')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="exam_name" id="exam_name" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="result_type">{{__('pages.Show Result Type')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="result_type" id="result_type" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <label for="board_ids" class="form-label">{{__("pages.Select Boards (keep blank if don't want to show)")}}</label>
                                    <select class="form-control select2" name="board_ids[]" id="board_ids" multiple>
                                        {{-- <option value="">--Select Boards--</option> --}}
                                        @foreach ($boards as $board)
                                            <option value="{{$board->id}}">{{$board->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="board_name">{{__('pages.Show Board Input Field')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="board_name" id="board_name" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="duration_id">{{__('pages.Show Duration')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="duration_id" id="duration_id" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <label for="subject_category_id" class="form-label">{{__("pages.Select Subject Category (keep blank if don't want to show)")}}</label>
                                        <select class="form-control select2" name="subject_category_id" id="subject_category_id">
                                            <option value="">--{{__("pages.Select Subject Category")}}--</option>
                                            @foreach ($subject_categories as $subject_category)
                                                <option value="{{$subject_category->id}}">{{$subject_category->name_en}}</option>
                                            @endforeach
                                        </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="subject_name">{{__("pages.Show Subject Input Field")}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="subject_name" id="subject_name" value="1" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="certificate_file">Show Certificate File Upload Input</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="certificate_file" id="certificate_file" value="1" autocomplete="off">
                                    </div>
                                </div>

                                
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="sl" class="form-label">{{__("pages.Serial")}}<span style="color:red;">*</span></label>
                                        <input type="number" class="form-control" name="sl" id="sl" placeholder="SL" autocomplete="off" value="{{old('sl')}}" required>
                                    </div>
                                </div>
                                

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__("pages.Status")}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{__("pages.Submit")}}</button>
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
    

        $('#SwitchCheck_5').click(function() {
            
            var checked = this.checked;
            if (checked) {
                $('.hidden_area').removeClass('d-none');
                $('.table-area').find('input').attr('required','required');

            } else {
                $('.hidden_area').addClass('d-none');
                $('.table-area').find('input').removeAttr('required');
            }
        });

        $('.add_more').click(function() {
            
            var clone = $(".table-area tbody tr:first-child").clone();
            $(".table-area tbody").append(clone);
            $(".table-area tbody tr:last-child td").find('input').val('');
        });

        function remove_tr(that) {
            $(that).closest('tr').remove();
        }
    </script>
    


@endpush

@push('css')
    <style>
        .table-area tbody tr:first-child .remove_more {
            display: none;
        }
    </style>
@endpush