@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Educational information'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Educational information')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Educational information')}}</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

            <div class="col-xxl-12">

                @include('backend.admin.partials.alert')

                <div class="card card-height-100">
                    <div class="card-body">
                        <form action="{{route('admin.user.store_education',Crypt::encryptString($user->id))}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="1" name="availablity">
                            <div class="row g-3">
                                <div class="row mt-4">
                                    <div class="card">
                                        <div class="card-header align-items-center">
                                            <h4 class="card-title">{{__('pages.Educational information')}}</h4>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($exam_forms as $exam_form)
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <thead>
                                                                  <tr>
                                                                    <th colspan="2">
                                                                        <input autocomplete="off" type="checkbox" value="1" name="academic_exam_form_id_data[{{$exam_form->id}}]"> {{$exam_form->name_en}}
                                                                        <input type="hidden" value="{{$exam_form->id}}" name="academic_exam_form_id[{{$exam_form->id}}]">
                                                                        <input type="hidden" value="{{$exam_form->name_en}}" name="academic_exam_form_name[{{$exam_form->id}}]">
                                                                    </th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if ($exam_form->roll == 1)
                                                                        <tr>
                                                                            <td>{{__('pages.Roll No')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter roll" name="roll[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->reg_no == 1)
                                                                        <tr>
                                                                            <td>{{__('pages.Registration No')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter no" name="reg_no[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->pass_year == 1)
                                                                        <tr>
                                                                            <td>{{__('pages.Passing year')}}</td>
                                                                            <td>
                                                                                <select name="pass_year[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @for ($x = 1950; $x <= date('Y'); $x++)
                                                                                        <option value="{{$x}}">{{$x}}</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->institute_ids)
                                                                        <tr>
                                                                            <td>{{__('pages.Institute')}}</td>
                                                                            <td>
                                                                                <select onchange="get_institute(this)" data-form_id="{{$exam_form->id}}" name="institute_id[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($institutes as $institute)
                                                                                        @if (in_array($institute->id,explode(',',$exam_form->institute_ids)))
                                                                                            <option value="{{$institute->id}}">{{$institute->name_en}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @if ($exam_form->institute_name == 1)
                                                                                        <option value="0">Others</option>
                                                                                    @endif
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->institute_name == 1)
                                                                        <tr  class="@if($exam_form->institute_ids) d-none @endif institute_name_{{$exam_form->id}}">
                                                                            <td>{{__('pages.Institute Name')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter name" name="institute_name[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->exam_category_id > 0)
                                                                        <tr>
                                                                            <td>{{__('pages.Exam')}}</td>
                                                                            <td>
                                                                                <select onchange="get_exam(this)" data-form_id="{{$exam_form->id}}" name="exam_id[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($exam_form->examInfos($exam_form->examCategoryInfo->exam_ids ?? '') as $exam)
                                                                                        {{-- @if (in_array($institute->id,explode(',',$exam_form->institute_ids))) --}}
                                                                                            <option value="{{$exam->id}}">{{$exam->name_en}}</option>
                                                                                        {{-- @endif --}}
                                                                                    @endforeach
                                                                                    @if ($exam_form->exam_name == 1)
                                                                                        <option value="0">Others</option>
                                                                                    @endif
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->exam_name == 1)
                                                                        <tr  class="@if($exam_form->exam_category_id) d-none @endif exam_name{{$exam_form->id}}">
                                                                            <td>{{__('pages.Exam Name')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter name" name="exam_name[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->board_ids)
                                                                        <tr>
                                                                            <td>{{__('pages.Board')}}</td>
                                                                            <td>
                                                                                <select onchange="get_board(this)" data-form_id="{{$exam_form->id}}" name="board_id[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($boards as $board)
                                                                                        @if (in_array($board->id,explode(',',$exam_form->board_ids)))
                                                                                            <option value="{{$board->id}}">{{$board->name_en}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @if ($exam_form->board_name == 1)
                                                                                        <option value="0">Others</option>
                                                                                    @endif
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->board_name == 1)
                                                                        <tr  class="@if($exam_form->board_ids) d-none @endif board_name{{$exam_form->id}}">
                                                                            <td>{{__('pages.Board Name')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter name" name="board_name[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->subject_category_id > 0)
                                                                        <tr>
                                                                            <td>{{__('pages.Subject')}}</td>
                                                                            <td>
                                                                                <select onchange="get_subject(this)" data-form_id="{{$exam_form->id}}" name="subject_id[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($exam_form->subjectInfos(($exam_form->subjectCategoryInfo->subject_ids ?? '')) as $subject)
                                                                                        {{-- @if (in_array($institute->id,explode(',',$exam_form->institute_ids))) --}}
                                                                                            <option value="{{$subject->id}}">{{$subject->name_en}}</option>
                                                                                        {{-- @endif --}}
                                                                                    @endforeach
                                                                                    @if ($exam_form->subject_name == 1)
                                                                                        <option value="0">Others</option>
                                                                                    @endif
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->subject_name == 1)
                                                                        <tr  class="@if($exam_form->subject_category_id) d-none @endif subject_name{{$exam_form->id}}">
                                                                            <td>{{__('pages.Subject Name')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter name" name="subject_name[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($exam_form->result_type == 1)
                                                                        <tr  class="">
                                                                            <td>{{__('pages.Result Type')}}</td>
                                                                            <td>
                                                                                <select onchange="result_type(this)" data-form_id="{{$exam_form->id}}" name="result_type[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach (result_types() as $index => $result_types)
                                                                                        <option value="{{$index}}">{{$result_types}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr  class="d-none result_{{$exam_form->id}}">
                                                                            <td>{{__('pages.Result')}}</td>
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="Enter result" name="result[{{$exam_form->id}}]">
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->duration_id > 0)
                                                                        <tr>
                                                                            <td>{{__('pages.Duration')}}</td>
                                                                            <td>
                                                                                <select name="duration_id[{{$exam_form->id}}]" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    @foreach ($durations as $duration)
                                                                                        {{-- @if (in_array($institute->id,explode(',',$exam_form->institute_ids))) --}}
                                                                                            <option value="{{$duration->id}}">{{$duration->name_en}}</option>
                                                                                        {{-- @endif --}}
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($exam_form->certificate_file == 1)
                                                                                <tr>
                                                                                    <td>Certificate File <small>(Image/ PDF)</small></td>
                                                                                    <td>
                                                                                        <input type="file" name="certificate_file[{{$exam_form->id}}]" class="form-control">
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                  
                                                                </tbody>
                                                              </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                </div>
                                
                                <div class="col-lg-12">
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script type="text/javascript">
      
        

        function get_institute(that){
            let form_id = $(that).attr('data-form_id');

            let value = $(that).find(':selected').val();
            if (value) {
                if (Number(value) != 0) {
                    $('.institute_name_'+form_id).addClass('d-none');
                } else {
                    $('.institute_name_'+form_id).removeClass('d-none');
                }
                
            } else {
                $('.institute_name_'+form_id).addClass('d-none');
            }
        };
        
        function get_exam(that){
            let form_id = $(that).attr('data-form_id');

            let value = $(that).find(':selected').val();
            if (value) {
                if (Number(value) != 0) {
                    $('.exam_name'+form_id).addClass('d-none');
                } else {
                    $('.exam_name'+form_id).removeClass('d-none');
                }
                
            } else {
                $('.exam_name'+form_id).addClass('d-none');
            }
        };
        
        function get_board(that){
            let form_id = $(that).attr('data-form_id');

            let value = $(that).find(':selected').val();
            if (value) {
                if (Number(value) != 0) {
                    $('.board_name'+form_id).addClass('d-none');
                } else {
                    $('.board_name'+form_id).removeClass('d-none');
                }
                
            } else {
                $('.board_name'+form_id).addClass('d-none');
            }
        };
        
        function get_subject(that){
            let form_id = $(that).attr('data-form_id');

            let value = $(that).find(':selected').val();
            if (value) {
                if (Number(value) != 0) {
                    $('.subject_name'+form_id).addClass('d-none');
                } else {
                    $('.subject_name'+form_id).removeClass('d-none');
                }
                
            } else {
                $('.subject_name'+form_id).addClass('d-none');
            }
        };
        function result_type(that){
            let form_id = $(that).attr('data-form_id');

            let value = $(that).find(':selected').val();
            if (value) {
                if (Number(value) == 5 || Number(value) == 6) {
                    $('.result_'+form_id).removeClass('d-none');
                    
                } else {
                    $('.result_'+form_id).addClass('d-none');
                }
                
            } else {
                $('.result_'+form_id).addClass('d-none');
            }
        };
     
    </script>


@endpush