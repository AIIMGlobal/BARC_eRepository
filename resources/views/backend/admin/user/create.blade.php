@extends('backend.layouts.app')

@section('title', 'Add New User | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">Add New User</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Add New User</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Add New User</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.user.index') }}" class="btn btn-primary">User List</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form id="createForm" action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="user_type" class="form-label mr-2">User Type: <span style="color:red;">*</span></label>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input user_type" type="radio" name="user_type" id="user_type4" value="4">

                                            <label class="form-check-label" for="user_type4">User</label>
                                        </div>
                                        
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input user_type" type="radio" name="user_type" id="user_type3" value="3">

                                            <label class="form-check-label" for="user_type3">Employee</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="name_en" class="form-label">Full Name: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="name_en" id="name_en" placeholder="Enter Your Full Name" value="{{ old('name_en') }}" required>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="employee_id" class="form-label">Employee ID: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="Enter Your User ID" value="{{ old('employee_id') }}" required>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="email" class="form-label">Email: <span style="color:red;">*</span></label>

                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="mobile" class="form-label">Mobile Number: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Valid Mobile Number" value="{{ old('mobile') }}" minlength="11" maxlength="14" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="role_id" class="form-label">Role: <span style="color:red;">*</span></label>

                                            <select class="form-control" name="role_id" id="role_id" required>
                                                <option value="">--Select Role--</option>

                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="office_id" class="form-label">Organization: <span style="color:red;">*</span></label>

                                            <select class="form-control select2" name="office_id" id="office_id" required>
                                                <option value="">--Select Organization--</option>

                                                @foreach ($offices as $office)
                                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="department_id" class="form-label">Department: <span style="color:red;">*</span></label>

                                            <select class="form-control" name="department_id" id="department_id" required>
                                                <option value="">--Select Department--</option>

                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="designation_id" class="form-label">Designation: <span style="color:red;">*</span></label>

                                            @if (Auth::user()->user_type == 4)
                                                <input type="text" class="form-control" name="designation" id="designation" placeholder="Enter Designation" value="{{ old('designation') }}">
                                            @else
                                                <select class="form-control" name="designation_id" id="designation_id" required>
                                                    <option value="">--Select Designation--</option>

                                                    @foreach ($designations as $designation)
                                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="dob" class="form-label">Date of Birth: </label>

                                            <input type="date" class="form-control" name="dob" id="dob" placeholder="Date of Birth" value="{{ old('dob') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="gender" class="form-label">Gender: </label>

                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">--Select Gender--</option>

                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="religion" class="form-label">Religion: </label>

                                            <select class="form-control" name="religion" id="religion">
                                                <option value="">--Select Religion--</option>

                                                <option value="Islam">Islam</option>
                                                <option value="Hinduism">Hinduism</option>
                                                <option value="Christianity">Christianity</option>
                                                <option value="Buddhism">Buddhism</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="birth_certificate_no" class="form-label">Birth Certificate: </label>

                                            <input type="text" class="form-control" name="birth_certificate_no" id="birth_certificate_no" value="{{ old('birth_certificate_no') }}" placeholder="Enter Birth Certificate">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="nid_no" class="form-label">NID number (If Applicable): </label>

                                            <input type="text" class="form-control" name="nid_no" id="nid_no" value="{{old('nid_no')}}" placeholder="Enter Valid NID number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="passport_no" class="form-label">Passport Number (If Applicable): </label>
                                            
                                            <input type="text" class="form-control" name="passport_no" id="passport_no" value="{{ old('passport_no') }}" placeholder="Enter Valid Passport number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="driving_license_no" class="form-label">Driving License Number (If Applicable): </label>

                                            <input type="text" class="form-control" name="driving_license_no" id="driving_license_no" value="{{ old('driving_license_no') }}" placeholder="Enter Valid Driving License number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="marital_status" class="form-label">Marital Status: </label>

                                            <select class="form-control" name="marital_status" id="marital_status" >
                                                <option value="">--Select Marital Status--</option>

                                                <option value="Married">Married</option>
                                                <option value="Single">Single</option>
                                                <option value="Widowed">Widowed</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="user_category_id" class="form-label">User Category: <span style="color:red;">*</span></label>

                                            <select class="form-control" name="user_category_id" id="user_category_id" required>
                                                <option value="">--Select User Category--</option>

                                                @foreach ($user_categories as $user_category)
                                                    <option value="{{ $user_category->id }}">{{ $user_category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="password" class="form-label">Password: <span style="color:red;">*</span></label>

                                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password (Minimum 8 Digit)" minlength="8" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="password_confirmation" class="form-label">Confirm Password: <span style="color:red;">*</span></label>

                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Re-Enter Password (Minimum 8 Digit)" minlength="8" required>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="start" class="form-label">Joining Date: </label>

                                            <input type="date" class="form-control" name="start" id="start" placeholder="Enter Date" value="{{ old('start') }}">
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-lg-12">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                    <label class="form-check-label form-label" for="SwitchCheck_5">Upload Documents</label>

                                                    <input class="form-check-input form-control" type="checkbox" role="switch" name="have_company_document" id="SwitchCheck_5" value="1" autocomplete="off">
                                                </div>
            
                                                <div class="hidden_area d-none">
                                                    <table class="table table-bordered table-sm text-left table-area">
                                                        <thead>
                                                            <tr>
                                                                <th>Documents: </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                                <td>
                                                                    <input type="file" id="file-0" name="document[]" multiple>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>

                                <div class="row mt-4">
                                    {{-- <div class="col-12">
                                        <h4 class="card-title">{{__('pages.Educational information')}}</h4>
                                                
                                        <div class="row">
                                            @foreach ($exam_forms as $exam_form)
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <thead>
                                                            <tr>
                                                            <th colspan="2">
                                                                <label>
                                                                    <input autocomplete="off" type="checkbox" value="1" name="academic_exam_form_id_data[{{$exam_form->id}}]"> {{$exam_form->name_en}}
                                                                </label>
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
                                                                                @if (in_array($institute->id,explode(',',$exam_form->institute_ids)))
                                                                                    <option value="{{$exam->id}}">{{$exam->name_en}}</option>
                                                                                @endif
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
                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others" name="exam_name[{{$exam_form->id}}]">
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
                                                                                @if (in_array($institute->id,explode(',',$exam_form->institute_ids)))
                                                                                    <option value="{{$subject->id}}">{{$subject->name_en}}</option>
                                                                                @endif
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
                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others Subject Name" name="subject_name[{{$exam_form->id}}]">
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
                                                                                @if (in_array($institute->id,explode(',',$exam_form->institute_ids)))
                                                                                    <option value="{{$duration->id}}">{{$duration->name_en}}</option>
                                                                                @endif
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
                                    </div> --}}

                                    <div class="col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Present Address: </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_division_id" class="form-label">Division: </label>

                                                        <select class="form-control select2" name="present_division_id" id="present_division_id">
                                                            <option value="">--Select Division--</option>

                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division->id }}">{{ $division->name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="present_district_id" class="form-label">District: <span style="color:red;">*</span></label>

                                                        <select class="form-control select2" name="present_district_id" id="present_district_id">
                                                            <option value="">--Select Division First--</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="present_upazila_id" class="form-label">Thana/Upazila</label>

                                                        <select class="form-control select2" name="present_upazila_id" id="present_upazila_id">
                                                            <option value="">--Select District First--</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="present_post_office" class="form-label">Post Office: </label>

                                                        <input type="text" class="form-control" name="present_post_office" id="present_post_office" placeholder="Enter your post office name" value="{{ old('present_post_office') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="present_post_code" class="form-label">Post Code: </label>
                                                        
                                                        <input type="number" class="form-control" name="present_post_code" id="present_post_code" placeholder="Four digit code" value="{{ old('present_post_code') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="present_village_road" class="form-label">Holding No/Village/Road: </label>

                                                        <textarea class="form-control" name="present_village_road" id="present_village_road" cols="30" rows="4" placeholder="Enter Holding No/Village/Road"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Permanent Address
                                                    <input type="checkbox" name="same_as_present_address" id="same_as_present_address">

                                                    <small style="font-weight: 400; font-size: 0.8em;">Same as Present Address</small>
                                                </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_division_id" class="form-label">Division: </label>

                                                        <select class="form-control select2" name="permanent_division_id" id="permanent_division_id">
                                                            <option value="">--Select Division--</option>

                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="permanent_district_id" class="form-label">District: </label>

                                                        <select class="form-control select2" name="permanent_district_id" id="permanent_district_id">
                                                            <option value="">--Select Division First--</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="permanent_upazila_id" class="form-label">Thana/Upazila: </label>

                                                        <select class="form-control select2" name="permanent_upazila_id" id="permanent_upazila_id">
                                                            <option value="">--Select District First--</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="permanent_post_office" class="form-label">Post Office: </label>

                                                        <input type="text" class="form-control" name="permanent_post_office" id="permanent_post_office" placeholder="Enter your post office name" value="{{ old('permanent_post_office') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="permanent_post_code" class="form-label">Post Code</label>
                                                        
                                                        <input type="number" class="form-control" name="permanent_post_code" id="permanent_post_code" placeholder="Four digit code" value="{{ old('permanent_post_code') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div>
                                                        <label for="permanent_village_road" class="form-label">Holding No/Village/Road: </label>

                                                        <textarea class="form-control" name="permanent_village_road" id="permanent_village_road" cols="30" rows="4" placeholder="Enter Holding No/Village/Road"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row mt-4">
                                    <div class="col-12">
                                        <h4 class="card-title">{{__('Educational information')}}</h4>

                                        <div class="row">
                                            @foreach ($exam_forms as $exam_form)
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <thead>
                                                            <tr>
                                                            <th colspan="2">
                                                                <label>
                                                                    <input autocomplete="off" type="checkbox" value="1" name="academic_exam_form_id_data[{{$exam_form->id}}]"> {{$exam_form->name_en}}
                                                                </label>
                                                                <input type="hidden" value="{{$exam_form->id}}" name="academic_exam_form_id[{{$exam_form->id}}]">
                                                                <input type="hidden" value="{{$exam_form->name_en}}" name="academic_exam_form_name[{{$exam_form->id}}]">
                                                            </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if ($exam_form->roll == 1)
                                                                <tr>
                                                                    <td>{{__('Roll No')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="Enter roll" name="roll[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            
                                                            @if ($exam_form->reg_no == 1)
                                                                <tr>
                                                                    <td>{{__('Registration No')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="Enter no" name="reg_no[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($exam_form->pass_year == 1)
                                                                <tr>
                                                                    <td>{{__('Passing year')}}</td>
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
                                                                    <td>{{__('Institute')}}</td>
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
                                                                    <td>{{__('Institute Name')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="Enter name" name="institute_name[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($exam_form->exam_category_id > 0)
                                                                <tr>
                                                                    <td>{{__('Exam')}}</td>
                                                                    <td>
                                                                        <select onchange="get_exam(this)" data-form_id="{{$exam_form->id}}" name="exam_id[{{$exam_form->id}}]" class="form-control">
                                                                            <option value="">Select</option>

                                                                            @foreach ($exam_form->examInfos($exam_form->examCategoryInfo->exam_ids ?? '') as $exam)
                                                                                <option value="{{$exam->id}}">{{$exam->name_en}}</option>
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
                                                                    <td>{{__('Exam Name')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others" name="exam_name[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($exam_form->board_ids)
                                                                <tr>
                                                                    <td>{{__('Board')}}</td>
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
                                                                    <td>{{__('Board Name')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="Enter name" name="board_name[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($exam_form->subject_category_id > 0)
                                                                <tr>
                                                                    <td>{{__('Subject')}}</td>
                                                                    <td>
                                                                        <select onchange="get_subject(this)" data-form_id="{{$exam_form->id}}" name="subject_id[{{$exam_form->id}}]" class="form-control">
                                                                            <option value="">Select</option>
                                                                            @foreach ($exam_form->subjectInfos(($exam_form->subjectCategoryInfo->subject_ids ?? '')) as $subject)
                                                                                <option value="{{$subject->id}}">{{$subject->name_en}}</option>
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
                                                                    <td>{{__('Subject Name')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others Subject Name" name="subject_name[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            
                                                            @if ($exam_form->result_type == 1)
                                                                <tr  class="">
                                                                    <td>{{__('Result Type')}}</td>
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
                                                                    <td>{{__('Result')}}</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" placeholder="Enter result" name="result[{{$exam_form->id}}]">
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($exam_form->duration_id > 0)
                                                                <tr>
                                                                    <td>{{__('Duration')}}</td>
                                                                    <td>
                                                                        <select name="duration_id[{{$exam_form->id}}]" class="form-control">
                                                                            <option value="">Select</option>

                                                                            @foreach ($durations as $duration)
                                                                                <option value="{{$duration->id}}">{{$duration->name_en}}</option>
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

                                    <div class="col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Present Address: </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_division_id" class="form-label">Division: </label>

                                                        <select class="form-control select2" name="present_division_id" id="present_division_id">
                                                            <option value="">--Select Division--</option>

                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_district_id" class="form-label">District: <span style="color:red;">*</span></label>

                                                        <select class="form-control select2" name="present_district_id" id="present_district_id">
                                                            <option value="">--Select Division First--</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_upazila_id" class="form-label">Thana/Upazila</label>

                                                        <select class="form-control select2" name="present_upazila_id" id="present_upazila_id">
                                                            <option value="">--Select District First--</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_post_office" class="form-label">Post Office: </label>

                                                        <input type="text" class="form-control" name="present_post_office" id="present_post_office" placeholder="Enter your post office name" value="{{ old('present_post_office') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_post_code" class="form-label">Post Code: </label>
                                                        
                                                        <input type="number" class="form-control" name="present_post_code" id="present_post_code" placeholder="Four digits code" value="{{ old('present_post_code') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="present_village_road" class="form-label">Village/Road: </label>

                                                        <textarea class="form-control" name="present_village_road" id="present_village_road" cols="30" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="card">

                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Permanent Address
                                                    <input type="checkbox" name="same_as_present_address" id="same_as_present_address">
                                                    <small style="font-weight: 400; font-size: 0.8em;">Same as present address</small>
                                                </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_division_id" class="form-label">Division: </label>

                                                        <select class="form-control select2" name="permanent_division_id" id="permanent_division_id">
                                                            <option value="">--Select Division--</option>

                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_district_id" class="form-label">District: </label>

                                                        <select class="form-control select2" name="permanent_district_id" id="permanent_district_id">
                                                            <option value="">--Select Division First--</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_upazila_id" class="form-label">Thana/Upazila: </label>

                                                        <select class="form-control select2" name="permanent_upazila_id" id="permanent_upazila_id">
                                                            <option value="">--Select District First--</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_post_office" class="form-label">Post Office: </label>
                                                        <input type="text" class="form-control" name="permanent_post_office" id="permanent_post_office" placeholder="Enter your post office name" value="{{ old('permanent_post_office') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_post_code" class="form-label">Post Code</label>
                                                        
                                                        <input type="number" class="form-control" name="permanent_post_code" id="permanent_post_code" placeholder="Four digits code" value="{{ old('permanent_post_code') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div>
                                                        <label for="permanent_village_road" class="form-label">Village/Road: </label>

                                                        <textarea class="form-control" name="permanent_village_road" id="permanent_village_road" cols="30" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Photo and Signature Upload</h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="col-12">
                                                            <div>
                                                                <label for="image" class="form-label">Photo: </label>

                                                                <input type="file" class="form-control" name="image" id="image" >
                                                            </div>
                                                        </div>
        
                                                        <div class="col-12 mt-4">
                                                            <div>
                                                                <label for="signature" class="form-label">Signature: </label>

                                                                <input type="file" class="form-control" name="signature" id="signature">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-5">
                                                        <div class="col-12">
                                                            <div>
                                                                <img id="image_preview" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/User-avatar.svg/2048px-User-avatar.svg.png" alt="User Image" width="80px;">
                                                            </div>
                                                        </div>
        
                                                        <div class="col-12 mt-4">
                                                            <div>
                                                                <img id="signature_preview" src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Signature" width="80px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                                        </div>
                                    </div>
                                </div>
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
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('backend.admin.partials.addressDynamic')

    <script>
        $(document).ready(function (e) {
            $('.select2').select2();
            
            $('#image').change(function() {
                let reader = new FileReader();

                reader.onload = (e) => { 
                    $('#image_preview').attr('src', e.target.result); 
                }

                reader.readAsDataURL(this.files[0]); 
            });

            $('#signature').change(function() {
                let reader = new FileReader();

                reader.onload = (e) => { 
                    $('#signature_preview').attr('src', e.target.result); 
                }

                reader.readAsDataURL(this.files[0]); 
            });

            $('#same_as_present_address').click(function() {
                if($(this).prop("checked") == true){
                    
                    let present_division_id = $("#present_division_id").val();
                    $('#permanent_division_id').prop('disabled', 'disabled');
                    $('#permanent_division_id').removeAttr('required');

                    let present_district_id = $("#present_district_id").val();
                    $('#permanent_district_id').prop('disabled', 'disabled');
                    $('#permanent_district_id').removeAttr('required');

                    let present_upazila_id = $("#present_upazila_id").val();
                    $('#permanent_upazila_id').prop('disabled', 'disabled');
                    $('#permanent_upazila_id').removeAttr('required');

                    let present_post_office = $("#present_post_office").val();
                    $('#permanent_post_office').prop('readonly', 'readonly');
                    // $('#permanent_post_office').removeAttr('required');

                    let present_post_code = $("#present_post_code").val();
                    $('#permanent_post_code').prop('readonly', 'readonly');
                    // $('#permanent_post_code').removeAttr('required');

                    let present_village_road = $("#present_village_road").val();
                    $('#permanent_village_road').prop('readonly', 'readonly');
                    // $('#permanent_village_road').removeAttr('required');
                } else if ($(this).prop("checked") == false) {
                    $("#permanent_division_id").prop('disabled', false);
                    // $('#permanent_division_id').prop('required',true);

                    $("#permanent_district_id").prop('disabled', false);
                    // $('#permanent_district_id').prop('required',true);

                    $("#permanent_upazila_id").prop('disabled', false);
                    // $('#permanent_upazila_id').prop('required',true);

                    $("#permanent_post_office").removeAttr('readonly');
                    // $('#permanent_post_office').prop('required',true);

                    $("#permanent_post_code").removeAttr('readonly');
                    // $('#permanent_post_code').prop('required',true);

                    $("#permanent_village_road").removeAttr('readonly');
                    // $('#permanent_village_road').prop('required',true);
                }
            });
            
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
        });
    </script>

    <script>
        $('#SwitchCheck_5').click(function() {
            var checked = this.checked;

            if (checked) {
                $('.hidden_area').removeClass('d-none');
                // $('.table-area').find('input').attr('required','required');
            } else {
                $('.hidden_area').addClass('d-none');
                $('.table-area').find('input').removeAttr('required');
            }
        });
    </script>

    <script>
        $("#file-0").fileinput({
            theme: 'fa5',
            showUpload: false,
            showBrowse: false,
            uploadUrl: '#',
            browseOnZoneClick: true,
            initialPreviewShowDelete: true,
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#createForm").on("submit", function(e) {
                e.preventDefault();

                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading...`);
                
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("button[type='submit']").prop("disabled", true);
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: response.message,
                                icon: 'success',
                                showCancelButton: false,
                            });

                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            Swal.fire('Error', response.message || 'An error occurred.', 'error');

                            $('#submitBtn').prop('disabled', false);
                            $('#submitBtn').html(`Submit`);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message || 'An error occurred.', 'error');
                    }
                });
            });
        });
    </script>
@endpush