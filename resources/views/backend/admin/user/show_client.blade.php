@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | Client Details')
@section('content')

<div class="page-content">
    @include('backend.admin.partials.alert')
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                {{-- <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" /> --}}
            </div>
        </div>
        <div class="">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{asset('storage/userImages')}}/{{$employee->userInfo->image ?? '-'}}" alt="user-img" class="img-thumbnail" style="max-height: 120px;" />
                    </div>
                </div>
                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h2 class="text-white mb-1">{{$employee->name_en}}</h2>
                        <p class="text-white-75" style="font-size: 1.4em;">
                            {{ $employee->userInfo->designation->name ?? ''}}
                        </p>
                        {{-- <div class="hstack text-white-50 gap-1">
                            <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>California, United States</div>
                            <div>
                                <i class="ri-building-line me-1 text-white-75 fs-16 align-middle"></i>Themesbrand
                            </div>
                        </div> --}}
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    {{-- <div class="d-flex">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-18 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">{{__('pages.Overview')}}</span>
                                </a>
                            </li>
                            
                            
                            
                        </ul>
                    </div> --}}
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">{{__('pages.Info')}}</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="ps-0" scope="row">{{__('pages.Full Name')}} :</th>
                                                            <td class="text-muted">{{$employee->name_en}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">{{__('pages.Mobile')}} :</th>
                                                            <td class="text-muted">{{$employee->mobile}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">{{__('pages.Email')}} :</th>
                                                            <td class="text-muted">{{$employee->email}}</td>
                                                        </tr>
                                                        
                                                        

                                                        @if (($employee->userInfo->retire_date != '') || ($employee->userInfo->retire_date != NULL))
                                                            <tr>
                                                                <th class="ps-0" scope="row">{{__('pages.Retire Date')}} :</th>
                                                                <td class="text-muted">{{ $employee->userInfo->retire_date ?? '-' }}</td>
                                                            </tr>
                                                        @endif
                                                        
                                                        
                                                    </tbody>
                                                </table>

                                                
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->
                                <div class="col-xxl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">{{__('pages.About')}}</h5>
                                            <div class="row">
                                                
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Date of Birth')}}</label>
                                                    <p>{{ $employee->userInfo->dob ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Gender')}}</label>
                                                    <p>{{ $employee->userInfo->gender ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Religion')}}</label>
                                                    <p>{{ $employee->userInfo->religion ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Birth Certificate')}}</label>
                                                    <p>{{ $employee->userInfo->birth_certificate_no ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.NID number')}}</label>
                                                    <p>{{ $employee->userInfo->nid_no ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Passport Number')}}</label>
                                                    <p>{{ $employee->userInfo->passport_no ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Marital Status')}}</label>
                                                    <p>{{ $employee->userInfo->marital_status ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Mobile Number')}}</label>
                                                    <p>{{ $employee->mobile }}</p>
                                                </div>
                                                
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Employee Role')}}</label>
                                                    <p>{{ $employee->role->name_en ?? ''}}</p>
                                                </div>
                                                
                                                <div class="col-6 col-md-4">
                                                    <label for="">{{__('pages.Signature')}}</label>
                                                    <img style="max-width: 200px;" src="{{asset('storage/signature')}}/{{$employee->userInfo->signature ?? '-'}}" alt="">
                                                </div>
                                            </div>
                                            <!--end row-->
                                        </div>
                                        <!--end card-body-->
                                    </div><!-- end card -->
                                </div>

                                <div class="col-xxl-12">

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">{{__('pages.Address')}}</h5>
                                            <div class="row">
                                                <div class="col-6 col-md-6">
                                                    <h5>{{__('pages.Present Address')}}</h5>
                                                    <label for="">{{__('pages.Division')}}</label>
                                                    <p>{{ $employee->userAddress->presentDivision->name ?? '' }}</p>
                                                    <label for="">{{__('pages.District')}}</label>
                                                    <p>{{ $employee->userAddress->presentDistrictName->name ?? '' }}</p>
                                                    <label for="">{{__('pages.Thana/Upazila')}}</label>
                                                    <p>{{ $employee->userAddress->presentUpazila->name ?? '' }}</p>
                                                    <label for="">{{__('pages.Post Office')}}</label>
                                                    <p>{{ $employee->userAddress->present_post_office ?? '' }}</p>
                                                    <label for="">{{__('pages.Post Code')}}</label>
                                                    <p>{{ $employee->userAddress->present_post_code ?? '' }}</p>
                                                    <label for="">{{__('pages.Village/Road')}}</label>
                                                    <p>{{ $employee->userAddress->present_address ?? '' }}</p>
                                                </div>
                                                <div class="col-6 col-md-6">
                                                    <h5>{{__('pages.Permanent Address')}}</h5>
                                                    <label for="">{{__('pages.Division')}}</label>
                                                    <p>{{ $employee->userAddress->permanentDivision->name ?? '' }}</p>
                                                    <label for="">{{__('pages.District')}}</label>
                                                    <p>{{ $employee->userAddress->permanentDistrict->name ?? '' }}</p>
                                                    <label for="">{{__('pages.Thana/Upazila')}}</label>
                                                    <p>{{ $employee->userAddress->permanentUpazila->name ?? '' }}</p>
                                                    <label for="">{{__('pages.Post Office')}}</label>
                                                    <p>{{ $employee->userAddress->permanent_post_office ?? '' }}</p>
                                                    <label for="">{{__('pages.Post Code')}}</label>
                                                    <p>{{ $employee->userAddress->permanent_post_code ?? '' }}</p>
                                                    <label for="">{{__('pages.Village/Road')}}</label>
                                                    <p>{{ $employee->userAddress->permanent_address ?? '' }}</p>
                                                </div>
                                            </div>
                                            <!--end row-->
                                        </div>
                                        <!--end card-body-->
                                    </div><!-- end card -->
                                    
                                    

                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        
                        
                    </div>
                    <!--end tab-content-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div><!-- container-fluid -->
</div>
@endsection

@push('script')
    <script>
        // $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');

       
        function printDiv(divName) {
            $('div').removeClass('table-responsive');
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

    <script>
        $('#SwitchCheck11').click(function(){
            if($(this).prop("checked") == true){
                $("#retire_date_box").css("display", "block");
                $("#retire_date_box").prop('required',true);
            }else if($(this).prop("checked") == false){
                $("#retire_date_box").css("display", "none");
                $("#retire_date_box").prop('required',false);
            }
        });
    </script>
@endpush

@push('css')
    <style>
        .img-thumbnail {
            max-height: 85px;
        }
        
        @media print
{    
    .remove_from_print
    {
        display: none !important;
    }
}
    </style>
@endpush