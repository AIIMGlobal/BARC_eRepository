@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Update Profile'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Update Profile')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Update Profile')}}</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Update Profile')}}</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-3">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{{url('storage/userImages').'/'.($user->userInfo->image ?? '')}}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="User Image">
                                    </div>
                                    <p style="color:rgb(4, 199, 4);">({{$user->role ? $user->role->display_name : ''}})</p>
                                    <h5 class="fs-16 mb-1">{{$user->name_en}} </h5>
                                    @if(auth()->user()->user_type == 4)
                                        <p class="text-muted mb-0">{{$user->userInfo->visitor_designation ?? '-'}}</p>
                                    @else
                                        <p class="text-muted mb-0">{{$user->designation ? $user->designation->name : '-'}}</p>
                                    @endif


                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{{url('storage/signature').'/'.($user->userInfo->signature ?? '')}}" class="avatar-xl img-thumbnail user-profile-image" alt="User Signature">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end card-->


                    </div>
                    <!--end col-->
                    <div class="col-xxl-9">
                        <div class="card mt-2">
                            <div class="card-header">
                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="true">
                                            <i class="fas fa-home"></i> {{__('pages.User Details')}}
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" aria-selected="false">
                                            <i class="far fa-user"></i> {{__('pages.Update Password')}}
                                        </a>
                                    </li>

                                </ul>
                            </div>

                            <div class="card-body p-4">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                        <form action="{{route('admin.update_profile')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="name_en" class="form-label">{{__('pages.Full Name')}} ({{__('pages.English')}})<span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" id="name_en" name="name_en" value="{{$user->name_en ?? ''}}" required>
                                                    </div>
                                                </div>
                                                
                                                {{-- <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="name_bn" class="form-label">{{__('pages.Full Name')}} ({{__('pages.Bangla')}})<span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" id="name_bn" name="name_bn" value="{{$user->name_bn ?? ''}}" required>
                                                    </div>
                                                </div> --}}

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="father_name_en" class="form-label">{{__('pages.Father\'s Name')}} ({{__('pages.English')}})</label>
                                                        <input type="text" class="form-control" id="father_name_en" name="father_name_en" value="{{$user->userInfo->f_name_en ?? 'N/A'}}">
                                                    </div>
                                                </div>

                                                {{-- <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="father_name_bn" class="form-label">{{__('pages.Father\'s Name')}} ({{__('pages.Bangla')}})</label>
                                                        <input type="text" class="form-control" id="father_name_bn" name="father_name_bn" value="{{$user->userInfo->f_name_bn ?? 'N/A'}}">
                                                    </div>
                                                </div> --}}

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mother_name_en" class="form-label">{{__('pages.Mother\'s Name')}} ({{__('pages.English')}})</label>
                                                        <input type="text" class="form-control" id="mother_name_en" name="mother_name_en" value="{{$user->userInfo->m_name_en ?? 'N/A'}}">
                                                    </div>
                                                </div>

                                                {{-- <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mother_name_bn" class="form-label">{{__('pages.Mother\'s Name')}} ({{__('pages.Bangla')}})</label>
                                                        <input type="text" class="form-control" id="mother_name_bn" name="mother_name_bn" value="{{$user->userInfo->m_name_bn ?? 'N/A'}}">
                                                    </div>
                                                </div> --}}

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">{{__('pages.Email')}}<span style="color:red;">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{$user->email ?? '-'}}" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mobile" class="form-label">{{__('pages.Mobile Number')}}<span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{$user->mobile ?? ''}}" minlength="11" maxlength="14" required>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="dob" class="form-label">{{__('pages.Date of Birth')}}</label>
                                                        <input type="date" class="form-control" name="dob" id="dob" placeholder="Date of birth" value="{{$user->userInfo->dob ?? 'N/A'}}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="gender" class="form-label">{{__('pages.Gender')}}</label>
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
                                                        <label for="religion" class="form-label">{{__('pages.Religion')}}</label>
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

                                                <div class="col-lg-6">
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
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="marital_status" class="form-label">{{__('pages.Marital Status')}}</label>
                                                        <select id="my-select" class="form-control" name="marital_status" id="marital_status">
                                                            <option value="">--Select Marital Status--</option>
                                                            <option @if(($user->userInfo->marital_status ?? '') == 'Married') selected @endif value="Married">Married</option>
                                                            <option @if(($user->userInfo->marital_status ?? '') == 'Single') selected @endif value="Single">Single</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="employee_id" class="form-label">{{__('pages.Employee ID')}}<span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="Enter employee id" value="{{$user->userInfo->employee_id ?? 'N/A'}}" required>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">{{__('pages.Photo')}}</label>
                                                        <input type="file" class="form-control" name="image" id="image">
                                                    </div>
                                                </div>
 
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="signature" class="form-label">{{__('pages.Signature')}}</label>
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
                                                                        <label for="present_division_id" class="form-label">{{__('pages.Division')}}</label>
                                                                        <select  class="form-control select2" name="present_division_id" id="present_division_id">
                                                                            <option value="">--Select Division--</option>
                                                                            @foreach ($divisions as $division)
                                                                                <option @if(($employee->userAddress->present_division_id ?? '') == $division->id) selected @endif value="{{$division->id}}">{{$division->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="present_district_id" class="form-label">{{__('pages.District')}}</label>
                                                                        <select  class="form-control select2" name="present_district_id" id="present_district_id">
                                                                            <option value="">--Select District--</option>
                                                                            @foreach ($presentDistricts as $pd)
                                                                                <option @if(($employee->userAddress->present_district_id ?? '') == $pd->id) selected @endif value="{{$pd->id}}">{{$pd->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="present_upazila_id" class="form-label">{{__('pages.Thana/Upazila')}}</label>
                                                                        <select  class="form-control select2" name="present_upazila_id" id="present_upazila_id">
                                                                            <option value="">--Select Thana/Upazila--</option>
                                                                            @foreach ($presentUpazilas as $pu)
                                                                                <option @if(($employee->userAddress->present_upazila_id ?? '') == $pu->id) selected @endif value="{{$pu->id}}">{{$pu->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="present_post_office" class="form-label">{{__('pages.Post Office')}}</label>
                                                                        <input type="text" class="form-control" name="present_post_office" id="present_post_office" placeholder="Enter your post office name" value="{{$user->userAddress->present_post_office ?? ''}}">
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="present_post_code" class="form-label">{{__('pages.Post Code')}}</label>
                                                                        <input type="number" class="form-control" name="present_post_code" id="present_post_code" placeholder="Four digits code" value="{{$user->userAddress->present_post_code ?? ''}}">
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="present_village_road" class="form-label">{{__('pages.Village/Road')}}</label>
                                                                        <textarea class="form-control" name="present_village_road" id="present_village_road" cols="30" rows="4">{{$user->userAddress->present_address ?? ''}}</textarea>
                                                                    </div>
                                                                </div>
                
                                                            </div>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="card">
                
                                                            <div class="card-header align-items-center d-flex">
                                                                <h4 class="card-title mb-0 flex-grow-1">
                                                                    {{__('pages.Permanent Address')}}
                                                                    <input @if(($user->userAddress->same_as_present_address ?? '') == 1) checked @endif value="1" type="checkbox" name="same_as_present_address" id="same_as_present_address">
                                                                    <small style="font-weight: 400; font-size: 0.8em;">Same as present address</small>
                                                                </h4>
                                                            </div>
                
                                                            <div class="card-body">
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="permanent_division_id" class="form-label">{{__('pages.Division')}}</label>
                                                                        <select  class="form-control select2" name="permanent_division_id" id="permanent_division_id">
                                                                            <option value="">--Select Division--</option>
                                                                            @foreach ($divisions as $division)
                                                                                <option @if(($division->id == ($employee->userAddress->permanent_division_id ?? ''))) selected @endif value="{{$division->id}}">{{$division->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="permanent_district_id" class="form-label">{{__('pages.District')}}</label>
                                                                        <select  class="form-control select2" name="permanent_district_id" id="permanent_district_id" >
                                                                            <option value="">--Select District--</option>
                                                                            @foreach ($permanentDistricts as $district)
                                                                                <option @if(($district->id == ($employee->userAddress->permanent_district_id ?? ''))) selected @endif value="{{$district->id}}">{{$district->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="permanent_upazila_id" class="form-label">{{__('pages.Thana/Upazila')}}</label>
                                                                        <select  class="form-control select2" name="permanent_upazila_id" id="permanent_upazila_id">
                                                                            <option value="">--Select Thana/Upazila--</option>
                                                                            @foreach ($permanentUpazilas as $upazila)
                                                                                <option @if(($upazila->id == ($employee->userAddress->permanent_upazila_id ?? ''))) selected @endif value="{{$upazila->id}}">{{$upazila->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="post_office" class="form-label">{{__('pages.Post Office')}}</label>
                                                                        <input type="text" class="form-control" name="permanent_post_office" id="permanent_post_office" placeholder="Enter your post office name" value="{{$user->userAddress->permanent_post_office ?? 'N/A'}}">
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="post_code" class="form-label">{{__('pages.Post Code')}}</label>
                                                                        <input type="number" class="form-control" name="permanent_post_code" id="permanent_post_code" placeholder="Four digits code" value="{{$user->userAddress->permanent_post_code ?? 'N/A'}}">
                                                                    </div>
                                                                </div>
                
                                                                <div class="col-12">
                                                                    <div>
                                                                        <label for="village_road" class="form-label">{{__('pages.Village/Road')}}</label>
                                                                        <textarea class="form-control" name="permanent_village_road" id="permanent_village_road" cols="30" rows="4"> {{$user->userAddress->permanent_address ?? 'N/A'}}</textarea>
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
                                            <input type="hidden" name="id" value="{{$user->id}}">
                                            <div class="row g-2">
                                                <div class="col-lg-4">
                                                    <div>
                                                        <label for="oldpasswordInput" class="form-label">{{__('pages.Old Password')}}<span style="color: red;">*</span></label>
                                                        <div class="input-group mb-3">
                                                            <input type="password" class="form-control" id="oldpasswordInput" name="oldPassword" placeholder="current password" required>
                                                            <div onclick="change_input_type(this)" class="input-group-append">
                                                                <span class="input-group-text"><i class="ri-eye-line"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-4">
                                                    <div>
                                                        <label for="newpasswordInput" class="form-label">{{__('pages.New Password')}} <span style="color: red;">*</span></label>
                                                        <div class="input-group mb-3">
                                                            <input type="password" class="form-control" id="newpasswordInput" name="newPassword" placeholder=" new password" required>
                                                            <div onclick="change_input_type(this)" class="input-group-append">
                                                                <span class="input-group-text"><i class="ri-eye-line"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-4">
                                                    <div>
                                                        <label for="confirmpasswordInput" class="form-label">{{__('pages.Confirm Password')}}<span style="color: red;">*</span></label>
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
                                                        <button type="submit" class="btn btn-success" id="changePassButton">{{__('pages.Update Password')}}</button>
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
    <script>
        $("#confirmpasswordInput").change(function(){
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
            $('#same_as_present_address').click(function(){
                if($(this).prop("checked") == true){
                    
                    $('#permanent_division_id').prop('disabled','disabled');
                    // $('#permanent_division_id').removeAttr('required');

                    $('#permanent_district_id').prop('disabled','disabled');
                    // $('#permanent_district_id').removeAttr('required');

                    $('#permanent_upazila_id').prop('disabled','disabled');
                    // $('#permanent_upazila_id').removeAttr('required');

                    $('#permanent_post_office').prop('disabled', 'disabled');
                    // $('#permanent_post_office').removeAttr('required');

                    $('#permanent_post_code').prop('disabled', 'disabled');
                    // $('#permanent_post_code').removeAttr('required');

                    $('#permanent_village_road').prop('disabled', 'disabled');
                    // $('#permanent_village_road').removeAttr('required');

                }else if($(this).prop("checked") == false){
                    $("#permanent_division_id").prop('disabled', false);
                    // $('#permanent_division_id').prop('required',true);

                    $("#permanent_district_id").prop('disabled', false);
                    // $('#permanent_district_id').prop('required',true);

                    $("#permanent_upazila_id").prop('disabled', false);
                    // $('#permanent_upazila_id').prop('required',true);

                    $("#permanent_post_office").prop('disabled', false);
                    // $('#permanent_post_office').prop('required',true);

                    $("#permanent_post_code").prop('disabled', false);
                    // $('#permanent_post_code').prop('required',true);

                    $("#permanent_village_road").prop('disabled', false);
                    // $('#permanent_village_road').prop('required',true);
                }
            });
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


        function change_input_type(that){
            var attr_data = $(that).closest('.input-group').find('input').attr('type');
            if(attr_data == 'password') {
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
