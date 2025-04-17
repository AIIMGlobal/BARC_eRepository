@extends('backend.layouts.app')

@section('title', 'Update Profile | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Update Profile</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Update Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Update Profile</h4>

                            <div class="flex-shrink-0">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                @if ($user->userInfo && Storage::exists('public/userImages/' . ($user->userInfo->image ?? '')))
                                                    <img src="{{ asset('storage/userImages/' . ($user->userInfo->image ?? '')) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="User Image">
                                                @else
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/User-avatar.svg/2048px-User-avatar.svg.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="User Image">
                                                @endif
                                            </div>

                                            {{-- <p style="color:rgb(4, 199, 4);" class="mb-2">({{ $user->role->display_name ?? '' }})</p> --}}

                                            <h5 class="fs-16 mb-1">{{ $user->name_en }} </h5>

                                            @if (Auth::user()->user_type == 4)
                                                <p class="text-muted mb-0">{{ Auth::user()->userInfo->designation ?? '' }}</p>
                                            @else
                                                <p class="text-muted mb-0">{{ Auth::user()->userInfo->post->name ?? '' }}</p>
                                            @endif

                                            <div class="profile-user position-relative d-inline-block mx-auto my-2">
                                                @if ((Auth::user()->userInfo->signature ?? '') && Storage::exists('public/signature/' . Auth::user()->userInfo->signature))
                                                    <img src="{{ asset('storage/signature/' . (Auth::user()->userInfo->signature ?? '')) }}" class="avatar-xl img-thumbnail user-profile-image" alt="User Signature">
                                                @else
                                                    <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" class="avatar-xl img-thumbnail user-profile-image" alt="User Signature">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end card-->
                            </div>
                            <!--end col-->

                            <div class="col-md-9">
                                <div class="card mt-2">
                                    @include('backend.admin.partials.alert')

                                    <div class="card-header">
                                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="true">
                                                    <i class="fas fa-home"></i> User Details
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" aria-selected="false">
                                                    <i class="far fa-user"></i> Update Password
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                                <form action="{{ route('admin.update_profile') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="name_en" class="form-label">Full Name: <span style="color:red;">*</span></label>

                                                                <input type="text" class="form-control" id="name_en" name="name_en" value="{{ $user->name_en ?? '' }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email: <span style="color:red;">*</span></label>

                                                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? '-' }}" required>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="mobile" class="form-label">Mobile Number: <span style="color:red;">*</span></label>
                                                                
                                                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ $user->mobile ?? '' }}" minlength="11" maxlength="14" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-sm-6 col-xsm-12">
                                                            <div class="mb-3">
                                                                <label for="office_id" class="form-label">Organization: <span style="color:red;">*</span></label>
                    
                                                                <select class="form-control select2" name="office_id" id="office_id" required>
                                                                    <option value="">--Select Organization--</option>
                    
                                                                    @foreach ($offices as $office)
                                                                        <option value="{{ $office->id }}" {{ $office->id == ($employee->userInfo->office_id ?? 0) ? 'selected' : '' }}>{{ $office->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                    
                                                        <div class="col-md-6 col-sm-6 col-xsm-12">
                                                            <div class="mb-3">
                                                                <label for="department_id" class="form-label">Department: <span style="color:red;">*</span></label>
                    
                                                                <select  class="form-control select2" name="department_id" id="department_id" required>
                                                                    <option value="">--Select Department--</option>
                    
                                                                    @foreach ($departments as $department)
                                                                        <option @if (($employee->userInfo->department_id ?? 0) == $department->id) selected @endif value="{{ $department->id }}">{{$department->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 col-sm-6 col-xsm-12">
                                                            <div class="mb-3">
                                                                <label for="designation_id" class="form-label">Designation: <span style="color:red;">*</span></label>
                    
                                                                @if ($employee->user_type == 4)
                                                                    <input type="text" class="form-control" name="designation" id="designation" placeholder="Enter Designation" value="{{ $employee->userInfo->designation ?? old('designation') }}">
                                                                @else
                                                                    <select  class="form-control select2" name="designation_id" id="designation_id" required>
                                                                        <option value="">--Select Designation--</option>
                    
                                                                        @foreach ($designations as $designation)
                                                                            <option @if (($employee->userInfo->designation_id ?? 0) == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="dob" class="form-label">Date of Birth</label>

                                                                <input type="date" class="form-control" name="dob" id="dob" placeholder="Date of birth" value="{{ $user->userInfo->dob ?? 'N/A' }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="gender" class="form-label">Gender</label>

                                                                <select id="my-select" class="form-control" name="gender" id="gender">
                                                                    <option value="">--Select Gender--</option>

                                                                    <option @if(($user->userInfo->gender ?? '') == 'Male') selected @endif value="Male">Male</option>
                                                                    <option @if(($user->userInfo->gender ?? '') == 'Female') selected @endif value="Female">Female</option>
                                                                    <option @if(($user->userInfo->gender ?? '') == 'Other') selected @endif value="Other">Other</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="religion" class="form-label">Religion</label>

                                                                <select id="my-select" class="form-control" name="religion" id="religion">
                                                                    <option value="">--Select Religion--</option>

                                                                    <option @if(($user->userInfo->religion ?? 'N/A') == 'Islam') selected @endif value="Islam">Islam</option>
                                                                    <option @if(($user->userInfo->religion ?? 'N/A') == 'Hinduism') selected @endif value="Hinduism">Hinduism</option>
                                                                    <option @if(($user->userInfo->religion ?? 'N/A') == 'Christianity') selected @endif value="Christianity">Christianity</option>
                                                                    <option @if(($user->userInfo->religion ?? 'N/A') == 'Buddhism') selected @endif value="Buddhism">Buddhism</option>
                                                                    <option @if(($user->userInfo->religion ?? 'N/A') == 'Others') selected @endif value="Others">Others</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        {{-- <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="birth_certificate_no" class="form-label">{{__('pages.Birth Certificate')}}</label>
                                                                
                                                                <input type="text" class="form-control" name="birth_certificate_no" id="birth_certificate_no" value="{{$user->userInfo->birth_certificate_no ?? 'N/A'}}" placeholder="Enter Valid Birth Certificate number">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="nid_no" class="form-label">{{__('pages.NID number')}} ({{__('pages.If Applicable')}})</label>

                                                                <input type="text" class="form-control" name="nid_no" id="nid_no" value="{{$user->userInfo->nid_no ?? 'N/A'}}" placeholder="Enter Valid NID number">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="passport_no" class="form-label">{{__('pages.Passport Number')}} ({{__('pages.If Applicable')}})</label>

                                                                <input type="text" class="form-control" name="passport_no" id="passport_no" value="{{$user->userInfo->passport_no ?? 'N/A'}}" placeholder="Enter Valid Passport number">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="driving_license_no" class="form-label">{{__('pages.Driving License Number')}} ({{__('pages.If Applicable')}})</label>

                                                                <input type="text" class="form-control" name="driving_license_no" id="driving_license_no" value="{{$user->userInfo->driving_license_no ?? 'N/A'}}" placeholder="Enter Valid Driving License number">
                                                            </div>
                                                        </div> --}}

                                                        {{-- <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="marital_status" class="form-label">{{__('pages.Marital Status')}}</label>

                                                                <select id="my-select" class="form-control" name="marital_status" id="marital_status">
                                                                    <option value="">--Select Marital Status--</option>

                                                                    <option @if(($user->userInfo->marital_status ?? '') == 'Married') selected @endif value="Married">Married</option>
                                                                    <option @if(($user->userInfo->marital_status ?? '') == 'Single') selected @endif value="Single">Single</option>
                                                                </select>
                                                            </div>
                                                        </div> --}}

                                                        {{-- <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="employee_id" class="form-label">{{__('pages.Employee ID')}}<span style="color:red;">*</span></label>

                                                                <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="Enter employee id" value="{{$user->userInfo->employee_id ?? 'N/A'}}" required>
                                                            </div>
                                                        </div> --}}

                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Photo</label>

                                                                <input type="file" class="form-control" name="image" id="image">
                                                            </div>
                                                        </div>
        
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="signature" class="form-label">Signature</label>

                                                                <input type="file" class="form-control" name="signature" id="signature" >
                                                            </div>
                                                        </div>

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

                                                        <div class="col-lg-12">
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-success">Update</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!--end row-->
                                                </form>
                                            </div>
                                            <!--end tab-pane-->

                                            <div class="tab-pane" id="changePassword" role="tabpanel">
                                                <form action="{{route('admin.user.updatePassword')}}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="id" value="{{ $user->id }}">

                                                    <div class="row g-2">
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="oldpasswordInput" class="form-label">Old Password: <span style="color: red;">*</span></label>

                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control" id="oldpasswordInput" name="oldPassword" placeholder="Current Password" required>
                                                                    <div onclick="change_input_type(this)" class="input-group-append">
                                                                        <span class="input-group-text"><i class="ri-eye-line"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end col-->

                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="newpasswordInput" class="form-label">New Password: <span style="color: red;">*</span></label>

                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control" id="newpasswordInput" name="newPassword" placeholder="New password" required>
                                                                    <div onclick="change_input_type(this)" class="input-group-append">
                                                                        <span class="input-group-text"><i class="ri-eye-line"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end col-->

                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="confirmpasswordInput" class="form-label">Confirm Password: <span style="color: red;">*</span></label>

                                                                <div class="input-group mb-3">
                                                                    <input  type="password" class="form-control" id="confirmpasswordInput" name="confirmPassword" placeholder="Confirm password" required>
                                                                    <div onclick="change_input_type(this)" class="input-group-append">
                                                                        <span class="input-group-text"><i class="ri-eye-line"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end col-->

                                                        <div class="col-lg-12">
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-success" id="changePassButton">Update Password</button>
                                                            </div>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </form>
                                            </div>
                                            <!--end tab-pane-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
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
    @include('backend.admin.partials.addressDynamic')

    <script>
        $("#confirmpasswordInput").change(function() {
            let newPassword = $("#newpasswordInput").val();
            let confirmPassword = $("#confirmpasswordInput").val();

            if(newPassword != confirmPassword){
                $("#confirmpasswordInput").css("border", "1px solid red");
                $("#changePassButton").attr("disabled", true);
            }else{
                $("#confirmpasswordInput").css("border", "1px solid #ced4da");
                $("#changePassButton").attr("disabled", false);
            }
        });
    </script>

    <script>
        $(document).ready(function (e) {
            $('#same_as_present_address').click(function() {
                if ($(this).prop("checked") == true) {
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
        });

        function change_input_type(that){
            var attr_data = $(that).closest('.input-group').find('input').attr('type');

            if (attr_data == 'password') {
                $(that).closest('.input-group').find('input').attr('type','text');
                $(that).closest('.input-group').find('i').removeClass('ri-eye-line');
                $(that).closest('.input-group').find('i').addClass('ri-eye-off-line');
            } else {
                $(that).closest('.input-group').find('input').attr('type','password');
                $(that).closest('.input-group').find('i').removeClass('ri-eye-off-line');
                $(that).closest('.input-group').find('i').addClass('ri-eye-line');
                
            }
        }
    </script>
@endpush