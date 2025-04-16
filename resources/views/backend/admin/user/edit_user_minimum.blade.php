@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | ইউজার আপডেট')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">ইউজার আপডেট</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">ইউজার আপডেট</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">ইউজার আপডেট</h4>
                    <div class="flex-shrink-0">
                        {{-- <a href="{{url()->previous()}}" class="btn btn-primary">Back</a> --}}
                    </div>
                </div>




                <div class="row">
                    <div class="col-xxl-3">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{{url('storage/userImages').'/'.$user->image}}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="User Image">
                                    </div>
                                    <p style="color:rgb(4, 199, 4);">({{$user->role ? $user->role->display_name : ''}})</p>
                                    <h5 class="fs-16 mb-1">{{$user->full_name}} </h5>
                                    <p class="text-muted mb-0">{{$user->designation ? $user->designation->name : '-'}}</p>
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
                                            <i class="fas fa-home"></i> ব্যবহারকারি বিস্তারিত
                                        </a>
                                    </li>
                                    @can('change_any_user_pass')
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" aria-selected="false">
                                                <i class="far fa-user"></i> পাসওয়ার্ড পরিবর্তন করুন
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>

                            <div class="card-body p-4">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                        <form action="{{route('admin.user.updateUserMinimum')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="first_name" class="form-label">নামের প্রথম অংশ</label>
                                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{$user->first_name ?? ''}}">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="last_name" class="form-label">নামের শেষাংশ</label>
                                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{$user->last_name ?? ''}}">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mobile" class="form-label">মোবাইল নম্বর</label>
                                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{$user->mobile ?? ''}}">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">ই-মেইল</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{$user->email ?? '-'}}">
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">ঠিকানা</label>
                                                        <input type="text" class="form-control" id="address" name="address" value="{{$user->address ?? '-'}}">
                                                    </div>
                                                </div>
                                                <!--end col-->

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="division_id" class="form-label">--অঞ্চল নির্বাচন করুন--<span style="color:red;">*</span></label>
                                                        <select name="division_id" id="division_id" class="form-control">
                                                            <option value="">--নির্বাচন করুন--</option>
                                                            @foreach ($divisions as $division)
                                                                <option @if($division->id == $user->division_id) selected @endif value="{{$division->id}}">{{$division->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="district_id" class="form-label">--জেলা নির্বাচন করুন--<span style="color:red;">*</span></label>
                                                        <select name="district_id" id="district_id" class="form-control">
                                                            @if ($user->district_id)
                                                                <option value="{{$user->district_id}}">{{$user->district ? $user->district->name : 'District Name'}}</option>    
                                                            @else
                                                                <option value="">--নির্বাচন করুন--</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="upazila_id" class="form-label">--উপজেলা নির্বাচন করুন--<span style="color:red;">*</span></label>
                                                        <select name="upazila_id" id="upazila_id" class="form-control">
                                                            @if ($user->upazila_id)
                                                                <option value="{{$user->upazila_id}}">{{$user->upazila ? $user->upazila->name : 'Upazila Name'}}</option>    
                                                            @else
                                                                <option value="">--নির্বাচন করুন--</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                                
                                                <div class="col-lg-4">
                                                    <div>
                                                        <label for="image" class="form-label">ব্যবহারকারীর ছবি পরিবর্তন</label>
                                                        <input id="image" type="file" class="form-control" name="image">
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">স্ট্যাটাস</label>
                                                        <select name="status" id="horticulture_center_id" class="form-control">
                                                            @if ($user->status == 0)
                                                                <option value="0">নিষ্ক্রিয়</option>
                                                            @else
                                                                <option value="1">সক্রিয়</option>
                                                            @endif
                                                            {{-- <option value="">--নির্বাচন করুন--</option>
                                                            <option @if($user->status == 1) selected @endif value="1">সক্রিয়</option>
                                                            <option @if($user->status == 0) selected @endif value="0">নিষ্ক্রিয়</option> --}}
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-success">আপডেট করুন</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--end row-->
                                        </form>
                                    </div>
                                    <!--end tab-pane-->

                                    @can('change_any_user_pass')
                                        <div class="tab-pane" id="changePassword" role="tabpanel">
                                            <form action="{{route('admin.user.changeOtherUserPassword')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$user->id}}">
                                                <div class="row g-2">
                                                    {{-- <div class="col-lg-4">
                                                        <div>
                                                            <label for="oldpasswordInput" class="form-label">পুরাতন পাসওয়ার্ড <span style="color: red;">*</span></label>
                                                            <input type="password" class="form-control" id="oldpasswordInput" name="oldPassword" placeholder=" current password" required>
                                                        </div>
                                                    </div> --}}
                                                    <!--end col-->
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <label for="newpasswordInput" class="form-label">নতুন পাসওয়ার্ড <span style="color: red;">*</span></label>
                                                            <input type="password" class="form-control" id="newpasswordInput" name="newPassword" placeholder=" new password" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <label for="confirmpasswordInput" class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span style="color: red;">*</span></label>
                                                            <input type="password" class="form-control" id="confirmpasswordInput" name="confirmPassword" placeholder="Confirm password" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-success" id="changePassButton">পাসওয়ার্ড পরিবর্তন করুন</button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>

                                        </div>
                                    @endcan


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

        $("select[name='division_id']").change(function () {
            if ($(this).val()) {
                let divison_id = $(this).val();
                $.ajax({
                    url: "{{route('get_area')}}?division_id="+divison_id,
                    type: "get",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("select[name='district_id']").empty();
                        $("select[name='district_id']").append(response);
                        $("select[name='upazila_id']").empty();
                        $("select[name='upazila_id']").append('<option value="">নির্বাচন করুন</option>');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("select[name='district_id']").empty();
                        $("select[name='district_id']").append('<option value="">নির্বাচন করুন</option>');
                        $("select[name='upazila_id']").empty();
                        $("select[name='upazila_id']").append('<option value="">নির্বাচন করুন</option>');
                    }
                });
            } else {
                $("select[name='district_id']").empty();
                $("select[name='district_id']").append('<option value="">নির্বাচন করুন</option>');
                $("select[name='upazila_id']").empty();
                $("select[name='upazila_id']").append('<option value="">নির্বাচন করুন</option>');
            }
        });

        $("select[name='district_id']").change(function () {
            if ($(this).val()) {
                let district_id = $(this).val();
                $.ajax({
                    url: "{{route('get_area')}}?district_id="+district_id,
                    type: "get",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("select[name='upazila_id']").empty();
                        $("select[name='upazila_id']").append(response);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("select[name='upazila_id']").empty();
                        $("select[name='upazila_id']").append('<option value="">নির্বাচন করুন</option>');
                    }
                });
            } else {
                $("select[name='upazila_id']").empty();
                $("select[name='upazila_id']").append('<option value="">নির্বাচন করুন</option>');
            }
        });

    </script>
    <script>
        $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush
