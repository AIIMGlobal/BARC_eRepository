@extends('backend.layouts.app')

@section('title', 'Update User Information | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Update User Information</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Update User Information</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Update User Information</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.user.index') }}" class="btn btn-primary">User List</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form id="updateForm" action="{{ route('admin.user.update') }}" method="POST" enctype="multipart/form-data" autocapitalize="off">
                                @csrf
                                
                                {{-- <input type="hidden" value="1" name="availablity"> --}}
                                <input type="hidden" name="user_id" value="{{ $employee->id }}">
                                <input type="hidden" name="user_info_id" value="{{ $employee->userInfo->id ?? 0 }}">
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="user_type" class="form-label mr-2">User Type: <span style="color:red;">*</span></label>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input user_type" type="radio" name="user_type" id="user_type4" value="4" {{ $employee->user_type == 4 ? 'checked' : '' }}>

                                            <label class="form-check-label" for="user_type4">User</label>
                                        </div>
                                        
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input user_type" type="radio" name="user_type" id="user_type3" value="3" {{ $employee->user_type == 3 ? 'checked' : '' }}>

                                            <label class="form-check-label" for="user_type3">Employee</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="name_en" class="form-label">Full Name: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="name_en" id="name_en" placeholder="Enter Your Full Name" value="{{ $employee->name_en }}" required>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="employee_id" class="form-label">Employee ID: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="Enter Your Employee ID" value="{{ $employee->userInfo->employee_id ?? '' }}" required>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="email" class="form-label">Email: <span style="color:red;">*</span></label>

                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ $employee->email }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="mobile" class="form-label">Mobile Number: <span style="color:red;">*</span></label>

                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile Number" value="{{ $employee->mobile ?? '' }}" minlength="11" maxlength="14" required>
                                        </div>
                                    </div>

                                    @can('change_role')
                                        <div class="col-md-4 col-sm-6 col-xsm-12">
                                            <div>
                                                <label for="role_id" class="form-label">Role: <span style="color:red;">*</span></label>

                                                <select  class="form-control" name="role_id" id="role_id" required>
                                                    <option value="">--Select Role--</option>
                                                    
                                                    @foreach ($roles as $role)
                                                        <option @if ($employee->role_id == $role->id) selected @endif value="{{ $role->id }}">{{ $role->name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="role_id" value="{{ $employee->role_id }}">
                                    @endcan

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="office_id" class="form-label">Organization: <span style="color:red;">*</span></label>

                                            <select class="form-control select2" name="office_id" id="office_id" required>
                                                <option value="">--Select Organization--</option>

                                                @foreach ($offices as $office)
                                                    <option value="{{ $office->id }}" {{ $office->id == ($employee->userInfo->office_id ?? 0) ? 'selected' : '' }}>{{ $office->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="department_id" class="form-label">Department: <span style="color:red;">*</span></label>

                                            <select  class="form-control" name="department_id" id="department_id" required>
                                                <option value="">--Select Department--</option>

                                                @foreach ($departments as $department)
                                                    <option @if (($employee->userInfo->department_id ?? 0) == $department->id) selected @endif value="{{ $department->id }}">{{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="designation_id" class="form-label">Designation: <span style="color:red;">*</span></label>

                                            @if ($employee->user_type == 4)
                                                <input type="text" class="form-control" name="designation" id="designation" placeholder="Enter Designation" value="{{ $employee->userInfo->designation ?? old('designation') }}">
                                            @else
                                                <select  class="form-control" name="designation_id" id="designation_id" required>
                                                    <option value="">--Select Designation--</option>

                                                    @foreach ($designations as $designation)
                                                        <option @if (($employee->userInfo->designation_id ?? 0) == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="dob" class="form-label">Date of Birth: </label>

                                            <input type="date" class="form-control" name="dob" id="dob" placeholder="Date of birth" value="{{ $employee->userInfo->dob ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="gender" class="form-label">Gender: </label>

                                            <select  class="form-control" name="gender" id="gender">
                                                <option value="">--Select Gender--</option>

                                                <option @if(($employee->userInfo->gender ?? '') == 'Male') selected @endif value="Male">Male</option>
                                                <option @if(($employee->userInfo->gender ?? '') == 'Female') selected @endif value="Female">Female</option>
                                                <option @if(($employee->userInfo->gender ?? '') == 'Other') selected @endif value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="religion" class="form-label">Religion: </label>

                                            <select  class="form-control" name="religion" id="religion">
                                                <option value="">--Select Religion--</option>

                                                <option @if(($employee->userInfo->religion ?? 'N/A') == 'Islam') selected @endif value="Islam">Islam</option>
                                                <option @if(($employee->userInfo->religion ?? 'N/A') == 'Hinduism') selected @endif value="Hinduism">Hinduism</option>
                                                <option @if(($employee->userInfo->religion ?? 'N/A') == 'Christianity') selected @endif value="Christianity">Christianity</option>
                                                <option @if(($employee->userInfo->religion ?? 'N/A') == 'Buddhism') selected @endif value="Buddhism">Buddhism</option>
                                                <option @if(($employee->userInfo->religion ?? 'N/A') == 'Others') selected @endif value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="birth_certificate_no" class="form-label">{{__('pages.Birth Certificate')}}</label>

                                            <input type="text" class="form-control" name="birth_certificate_no" id="birth_certificate_no" value="{{$employee->userInfo->birth_certificate_no ?? 'N/A'}}" placeholder="Enter Valid Birth Certificate number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="nid_no" class="form-label">{{__('pages.NID number')}} ({{__('pages.If Applicable')}})</label>

                                            <input type="text" class="form-control" name="nid_no" id="nid_no" value="{{$employee->userInfo->nid_no ?? 'N/A'}}" placeholder="Enter Valid NID number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="passport_no" class="form-label">{{__('pages.Passport Number')}} ({{__('pages.If Applicable')}})</label>

                                            <input type="text" class="form-control" name="passport_no" id="passport_no" value="{{$employee->userInfo->passport_no ?? 'N/A'}}" placeholder="Enter Valid Passport number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="driving_license_no" class="form-label">{{__('pages.Driving License Number')}} ({{__('pages.If Applicable')}})</label>

                                            <input type="text" class="form-control" name="driving_license_no" id="driving_license_no" value="{{$employee->userInfo->driving_license_no ?? 'N/A'}}" placeholder="Enter Valid Driving License number">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="marital_status" class="form-label">{{__('pages.Marital Status')}}</label>

                                            <select  class="form-control" name="marital_status" id="marital_status">
                                                <option value="">--Select Marital Status--</option>

                                                <option @if(($employee->userInfo->marital_status ?? '') == 'Married') selected @endif value="Married">Married</option>
                                                <option @if(($employee->userInfo->marital_status ?? '') == 'Single') selected @endif value="Single">Single</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="user_category_id" class="form-label">User Category: <span style="color:red;">*</span></label>

                                            <select  class="form-control" name="user_category_id" id="user_category_id" required>
                                                <option value="">--Select User Category--</option>
                                                
                                                @foreach ($user_categories as $user_category)
                                                    <option @if($user_category->id == $employee->user_category_id) selected @endif value="{{ $user_category->id }}">{{ $user_category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="team_id" class="form-label">Team:</label>

                                            <select id="my-select" class="form-control" name="team_id" id="team_id">
                                                <option value="">--Select Team--</option>

                                                @foreach ($deputys as $deputy)
                                                    <option value="{{ $deputy->id }}" {{ $employee->team_id == $deputy->id ? 'selected' : '' }}>Team {{ $deputy->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="mobile" class="form-label">Joining Date</label>

                                            <input type="date" class="form-control" name="start" id="start" placeholder="Enter Date" value="{{ $employee->userInfo->start ?? 'N/A' }}">
                                        </div>
                                    </div> --}}

                                    {{-- @if (count($docs) > 0)
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">Company Documents:</h5>

                                                        <div class="hidden_area">
                                                            <table class="table table-bordered table-sm text-left table-area">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Document Title</th>
                                                                        <th>Documents</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($docs as $doc)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $doc->document_title }}
                                                                            </td>

                                                                            <td>
                                                                                <a href="{{ asset('storage/companyDocument') }}/{{ $doc->document }}" download="" target="#" rel="noopener noreferrer">Download</a>

                                                                                <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewDoc{{ $doc->id }}">View</button>

                                                                                <div class="modal fade" id="viewDoc{{ $doc->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                                                    <div class="modal-dialog">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalgridLabel">Document</h5>
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                            </div>

                                                                                            <div class="modal-body">
                                                                                                <div class="row g-3">
                                                                                                    <div class="col-12">
                                                                                                        <div>
                                                                                                            @if((pathinfo('storage/companyDocument/'.$doc->document, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/companyDocument/'.$doc->document, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/companyDocument/'.$doc->document, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/companyDocument/'.$doc->document, PATHINFO_EXTENSION) == 'gif'))
                                                                                                                <img src="{{ asset('storage/companyDocument') }}/{{ $doc->document }}" style="max-width: 400px;"/>
                                                                                                            @elseif (pathinfo('storage/companyDocument/'.$doc->document, PATHINFO_EXTENSION) == 'pdf')
                                                                                                                <iframe src="{{ asset('storage/companyDocument') }}/{{ $doc->document }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                                                            @else
                                                                                                                <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/companyDocument') }}/{{ $doc->document }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                    </div>
                                                                            
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="hstack gap-2 justify-content-end">
                                                                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                                                        </div>
                                                                                                    </div><!--end col-->
                                                                                                </div><!--end row-->
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                            <td>
                                                                                <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.user.companyDocDelete', Crypt::encryptString($doc->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                                    <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr> 
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}

                                    {{-- <div class="col-lg-12">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                    <label class="form-check-label form-label" for="SwitchCheck_5">Upload Company Documents</label>

                                                    <input class="form-check-input form-control" type="checkbox" role="switch" name="have_company_document" id="SwitchCheck_5" value="1" autocomplete="off">
                                                </div>
            
                                                <div class="hidden_area d-none">
                                                    <table class="table table-bordered table-sm text-left table-area">
                                                        <thead>
                                                            <tr>
                                                                <th>Company Documents: </th>
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

                                    <div class="row mt-4">
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
                                                                    <option @if(($employee->userAddress->present_division_id ?? '') == $division->id) selected @endif value="{{ $division->id }}">{{ $division->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="present_district_id" class="form-label">District: </label>

                                                            <select  class="form-control select2" name="present_district_id" id="present_district_id">
                                                                <option value="">--Select District--</option>

                                                                @foreach ($presentDistricts as $pd)
                                                                    <option @if(($employee->userAddress->present_district_id ?? '') == $pd->id) selected @endif value="{{ $pd->id }}">{{ $pd->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3 mt-3">
                                                        <div>
                                                            <label for="present_upazila_id" class="form-label">Thana/Upazila: </label>

                                                            <select  class="form-control select2" name="present_upazila_id" id="present_upazila_id">
                                                                <option value="">--Select Thana/Upazila--</option>

                                                                @foreach ($presentUpazilas as $pu)
                                                                    <option @if(($employee->userAddress->present_upazila_id ?? '') == $pu->id) selected @endif value="{{ $pu->id }}">{{ $pu->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="post_office" class="form-label">Post Office: </label>

                                                            <input type="text" class="form-control" name="present_post_office" id="post_office" placeholder="Enter your post office name" value="{{ $employee->userAddress->present_post_office ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="post_code" class="form-label">Post Code: </label>

                                                            <input type="number" class="form-control" name="present_post_code" id="post_code" placeholder="Four digits code" value="{{ $employee->userAddress->present_post_code ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="village_road" class="form-label">Village/Road: </label>

                                                            <textarea class="form-control" name="present_village_road" id="village_road" cols="30" rows="4" placeholder="Enter Village/Road">{{ $employee->userAddress->present_address ?? '' }}</textarea>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <div class="card">

                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1">Permanent Address: 
                                                        <input type="checkbox" name="same_as_present_address" id="same_as_present_address" @if(($employee->userAddress->same_as_present_address ?? '') == 1) checked @endif>
                                                        <small style="font-weight: 400; font-size: 0.8em;">Same as Present Address</small>
                                                    </h4>
                                                </div>

                                                <div class="card-body">
                                                    <div class="col-12">
                                                        <div>
                                                            <label for="permanent_division_id" class="form-label">Division: </label>

                                                            <select  class="form-control select2" name="permanent_division_id" id="permanent_division_id" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'disabled' : '' }}>
                                                                <option value="">--Select Division--</option>

                                                                @foreach ($divisions as $division)
                                                                    <option @if(($division->id == ($employee->userAddress->permanent_division_id ?? ''))) selected @endif value="{{ $division->id }}">{{ $division->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="permanent_district_id" class="form-label">District: </label>

                                                            <select  class="form-control select2" name="permanent_district_id" id="permanent_district_id" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'disabled' : '' }}>
                                                                <option value="">--Select District--</option>

                                                                @foreach ($permanentDistricts as $district)
                                                                    <option @if(($district->id == ($employee->userAddress->permanent_district_id ?? ''))) selected @endif value="{{ $district->id }}">{{ $district->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="permanent_upazila_id" class="form-label">Thana/Upazila</label>

                                                            <select  class="form-control select2" name="permanent_upazila_id" id="permanent_upazila_id" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'disabled' : '' }}>
                                                                <option value="">--Select Thana/Upazila--</option>

                                                                @foreach ($permanentUpazilas as $upazila)
                                                                    <option @if(($upazila->id == ($employee->userAddress->permanent_upazila_id ?? ''))) selected @endif value="{{ $upazila->id }}">{{ $upazila->name_en }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="post_office" class="form-label">Post Office: </label>

                                                            <input type="text" class="form-control" name="permanent_post_office" id="permanent_post_office" placeholder="Enter your post office name" value="{{ $employee->userAddress->permanent_post_office ?? '' }}" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'readonly' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="post_code" class="form-label">Post Code: </label>

                                                            <input type="number" class="form-control" name="permanent_post_code" id="permanent_post_code" placeholder="Four digits code" value="{{ $employee->userAddress->permanent_post_code ?? '' }}" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'readonly' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div>
                                                            <label for="village_road" class="form-label">Village/Road: </label>

                                                            <textarea class="form-control" name="permanent_village_road" id="permanent_village_road" cols="30" rows="4" placeholder="Enter Village/Road" {{ ($employee->userAddress->same_as_present_address ?? '') == 1 ? 'readonly' : '' }}>{{ $employee->userAddress->permanent_address ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header align-items-center">
                                                    <h4 class="card-title">{{__('pages.Educational information')}}</h4>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @foreach ($exam_forms as $exam_form)
                                                                @php
                                                                    $user_record = $exam_form->userAcademicRecord($employee->id,$exam_form->id);
                                                                @endphp

                                                                <div class="col-md-6">
                                                                    <table class="table table-borderless">
                                                                        <thead>
                                                                            <tr>
                                                                                @if ($user_record)
                                                                                    <th colspan="2">
                                                                                        <input autocomplete="off" type="checkbox" @if(($user_record->status ?? 0) == 1) checked @endif value="1" name="academic_exam_form_id_data[{{$exam_form->id}}]">
                                                                                        
                                                                                        <input type="hidden" value="{{$exam_form->id}}" name="academic_exam_form_id[{{$exam_form->id}}]">
                                                                                        <input readonly style="border:none" type="text" value="{{$exam_form->name_en}}" name="academic_exam_form_name[{{$exam_form->id}}]">
                                                                                    </th>
                                                                                @endif
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @if ($exam_form->roll == 1)
                                                                                <tr>
                                                                                    <td>{{__('pages.Roll No')}}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="Enter roll" name="roll[{{$exam_form->id}}]" value="{{$user_record->roll ?? ''}}">
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->reg_no == 1)
                                                                                <tr>
                                                                                    <td>{{__('pages.Registration No')}}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="Enter no" name="reg_no[{{$exam_form->id}}]" value="{{$user_record->reg_no ?? ''}}">
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
                                                                                                <option @if(($user_record->pass_year ?? 0) == $x) selected @endif value="{{$x}}">{{$x}}</option>
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
                                                                                                    <option @if(($user_record->institute_id ?? 0) == $institute->id) selected @endif value="{{$institute->id}}">{{$institute->name_en}}</option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            @if ($exam_form->institute_name == 1)
                                                                                                <option @if(($user_record->institute_id ?? 10) == 0) selected @endif value="0">Others</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->institute_name == 1)
                                                                                <tr  class="@if($exam_form->institute_ids) d-none @endif institute_name_{{$exam_form->id}}">
                                                                                    <td>{{__('pages.Institute Name')}}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="Enter name" name="institute_name[{{$exam_form->id}}]" value="{{$user_record->institute_name ?? ''}}">
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
                                                                                                <option @if(($user_record->exam_id ?? 0) == $exam->id) selected @endif value="{{$exam->id}}">{{$exam->name_en}}</option>
                                                                                            @endforeach

                                                                                            @if ($exam_form->exam_name == 1)
                                                                                                <option @if(($user_record->exam_id ?? 10) == 0) selected @endif value="0">Others</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->exam_name == 1)
                                                                                <tr  class="@if($exam_form->exam_category_id) d-none @endif exam_name{{$exam_form->id}}">
                                                                                    <td>{{__('pages.Exam Name')}}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others" name="exam_name[{{$exam_form->id}}]" value="{{$user_record->exam_name ?? ''}}">
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
                                                                                                    <option @if(($user_record->board_id ?? 0) == $board->id) selected @endif value="{{$board->id}}">{{$board->name_en}}</option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            @if ($exam_form->board_name == 1)
                                                                                                <option @if(($user_record->board_id ?? 10) == 0) selected @endif value="0">Others</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->board_name == 1)
                                                                                <tr  class="@if($exam_form->board_ids) d-none @endif board_name{{$exam_form->id}}">
                                                                                    <td>{{__('pages.Board Name')}}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="Enter name" name="board_name[{{$exam_form->id}}]" value="{{$user_record->board_name ?? ''}}">
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
                                                                                                <option @if(($user_record->subject_id ?? 0) == $subject->id) selected @endif value="{{$subject->id}}">{{$subject->name_en}}</option>
                                                                                            @endforeach

                                                                                            @if ($exam_form->subject_name == 1)
                                                                                                <option @if(($user_record->subject_id ?? 10) == 0) selected @endif value="0">Others</option>
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->subject_name == 1)
                                                                                <tr  class="@if($exam_form->subject_category_id) d-none @endif subject_name{{ $exam_form->id }}">
                                                                                    <td>{{ __('pages.Subject Name') }}</td>

                                                                                    <td>
                                                                                        <input type="text" class="form-control" placeholder="SSC/HSC/BSC/MSC/Others Subject Name" name="subject_name[{{ $exam_form->id }}]" value="{{ $user_record->subject_name ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            
                                                                            @if ($exam_form->result_type == 1)
                                                                                @if ($user_record)
                                                                                    <tr class="">
                                                                                        <td>Result Type</td>

                                                                                        <td>
                                                                                            <select onchange="result_type(this)" data-form_id="{{ $exam_form->id }}" name="result_type[{{ $exam_form->id }}]" class="form-control">
                                                                                                <option value="">--Select Result Type--</option>

                                                                                                @foreach (result_types() as $index => $result)
                                                                                                    <option @if(($user_record->result_type ?? 0) == $index) selected @endif value="{{ $index }}">{{ $result }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr class="{{ $user_record->result ? '' : 'd-none' }} result_{{ $exam_form->id }}">
                                                                                        <td>Result</td>

                                                                                        <td>
                                                                                            <input type="text" class="form-control" placeholder="Enter result" name="result[{{ $exam_form->id }}]" value="{{ $user_record->result ?? '' }}">
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endif
        
                                                                            @if ($exam_form->duration_id > 0)
                                                                                <tr>
                                                                                    <td>Duration</td>

                                                                                    <td>
                                                                                        <select name="duration_id[{{ $exam_form->id }}]" class="form-control">
                                                                                            <option value="">Select Duration</option>

                                                                                            @foreach ($durations as $duration)
                                                                                                <option @if(($user_record->duration_id ?? 0) == $duration->id) selected @endif value="{{ $duration->id }}">{{ $duration->name_en }}</option>
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
                                                                                        <input type="hidden" name="old_certificate_file[{{$exam_form->id}}]" class="form-control" value="{{$user_record->certificate_file ?? '' }}">
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
                                                                    <label for="image" class="form-label">Photo</label>
                                                                    <input type="file" class="form-control" name="image" id="image">
                                                                </div>
                                                            </div>
            
                                                            <div class="col-12 mt-4">
                                                                <div>
                                                                    <label for="signature" class="form-label">Signature</label>
                                                                    <input type="file" class="form-control" name="signature" id="signature" >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-5">
                                                            <div class="col-12">
                                                                <div>
                                                                    @if ($employee->userInfo && Storage::exists('public/userImages/' . $employee->userInfo->image))
                                                                        <img id="image_preview" src="{{ asset('storage/userImages/' . ($employee->userInfo->image ?? '')) }}" alt="User Image" width="80px;">
                                                                    @else
                                                                        <img id="image_preview" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/User-avatar.svg/2048px-User-avatar.svg.png" alt="User Image" width="80px;">
                                                                    @endif
                                                                </div>
                                                            </div>
            
                                                            <div class="col-12 mt-4">
                                                                <div>
                                                                    @if ($employee->userInfo && Storage::exists('public/signature/' . $employee->userInfo->signature))
                                                                        <img id="signature_preview" src="{{ asset('storage/signature/' . ($employee->userInfo->signature ?? '')) }}" alt="" width="80px;">
                                                                    @else
                                                                        <img id="signature_preview" src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="" width="80px;">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-success" id="submitBtn">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $('.select2').select2();
        
            $('#image').change(function() {
                let reader = new FileReader();

                reader.onload = (e) => { 
                    $('#image_preview').attr('src', e.target.result); 
                }

                reader.readAsDataURL(this.files[0]); 
            });

            $('#signature').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => { 
                $('#signature_preview').attr('src', e.target.result); 
                }
                reader.readAsDataURL(this.files[0]); 
            });

            // dynamic address AJAX for present district
            $("#present_division_id").on('change',function(e){
                e.preventDefault();

                let present_district_id = $("#present_district_id");
                let present_upazila_id = $("#present_upazila_id");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.districts')}}",
                    data: {_token:$('input[name=_token]').val(),
                    division_id: $(this).val()},

                    success:function(response){
                        $('option', present_district_id).remove();
                        $('option', present_upazila_id).remove();
                        $('#present_district_id').append('<option value="">--Select District--</option>');
                        $('#present_upazila_id').append('<option value="">--Select Upazila/Thana--</option>');
                        $.each(response, function(){
                            $('<option/>', {
                                'value': this.id,
                                'text': this.name_en
                            }).appendTo('#present_district_id');
                        });
                    }

                });
            });

            // dynamic address AJAX for present upazila/thana
            $("#present_district_id").on('change',function(e){
                e.preventDefault();

                let present_upazila_id = $("#present_upazila_id");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.upazilas')}}",
                    data: {_token:$('input[name=_token]').val(),
                    district_id: $(this).val()},

                    success:function(response){
                        $('option', present_upazila_id).remove();
                        $('#present_upazila_id').append('<option value="">--Select Upazila/Thana--</option>');
                        $.each(response, function(){
                            $('<option/>', {
                                'value': this.id,
                                'text': this.name_en
                            }).appendTo('#present_upazila_id');
                        });
                    }

                });
            });

            // dynamic address AJAX for permanent district
            $("#permanent_division_id").on('change',function(e){
                e.preventDefault();

                let permanent_district_id = $("#permanent_district_id");
                let permanent_upazila_id = $("#permanent_upazila_id");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.districts')}}",
                    data: {_token:$('input[name=_token]').val(),
                    division_id: $(this).val()},

                    success:function(response){
                        $('option', permanent_district_id).remove();
                        $('option', permanent_upazila_id).remove();
                        $('#permanent_district_id').append('<option value="">--Select District--</option>');
                        $('#permanent_upazila_id').append('<option value="">--Select District First--</option>');
                        $.each(response, function(){
                            $('<option/>', {
                                'value': this.id,
                                'text': this.name_en
                            }).appendTo('#permanent_district_id');
                        });
                    }

                });
            });

            // dynamic address AJAX for permanent upazila/thana
            $("#permanent_district_id").on('change',function(e){
                e.preventDefault();

                let permanent_upazila_id = $("#permanent_upazila_id");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.upazilas')}}",
                    data: {_token:$('input[name=_token]').val(),
                    district_id: $(this).val()},

                    success:function(response){
                        $('option', permanent_upazila_id).remove();
                        $('#permanent_upazila_id').append('<option value="">--Select Upazila/Thana--</option>');
                        $.each(response, function(){
                            $('<option/>', {
                                'value': this.id,
                                'text': this.name_en
                            }).appendTo('#permanent_upazila_id');
                        });
                    }

                });
            });

            // if checkbox is checked for same present and permanent address
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

                    $("#permanent_post_office").prop('readonly', false);
                    // $('#permanent_post_office').prop('required',true);

                    $("#permanent_post_code").prop('readonly', false);
                    // $('#permanent_post_code').prop('required',true);

                    $("#permanent_village_road").prop('readonly', false);
                    // $('#permanent_village_road').prop('required',true);
                }
            });
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
        }
    
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
        }
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
        }

        $('input').attr('autocomplete','off');
        $('select').attr('autocomplete','off');
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
            $("#updateForm").on("submit", function(e) {
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
                            $('#submitBtn').html(`Update`);
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