@extends('backend.layouts.app')

@section('title', 'View User Details | '.($global_setting->title ?? ""))

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

@section('content')
    <div class="page-content">
        @include('backend.admin.partials.alert')

        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">User Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="profile-foreground position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg">
                    {{-- <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" /> --}}
                </div>
            </div>

            <div class="mt-4">
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            @if (($employee->userInfo->image ?? '') && Storage::exists('public/userImages/' . $employee->userInfo->image))
                                <img src="{{ asset('storage/userImages/' . ($employee->userInfo->image ?? ''))}}" alt="User Image" class="img-thumbnail" style="max-height: 120px;" />
                            @else
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/User-avatar.svg/2048px-User-avatar.svg.png" alt="User Image" class="img-thumbnail" style="max-height: 120px;" />
                            @endif
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-2">
                            <h2 class="text-white mb-1">{{ $employee->name_en }}</h2>

                            <p class="text-white-75" style="font-size: 1.2em; margin-bottom: 0;">
                                @if ($employee->user_type == 4)
                                    <td>{{ $employee->userInfo->designation ?? '' }}</td>
                                @else
                                    <td>{{ $employee->userInfo->post->name ?? '' }}</td>
                                @endif
                            </p>

                            <p class="text-white-75" style="font-size: 1.2em;">
                                <td>{{ $employee->userInfo->office->name ?? $global_setting->title }}</td>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="tab-content pt-4 text-muted">
                            <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-1" style="font-size: 1.5em; font-weight: 600;">Info</h5>

                                                <div class="table-responsive">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Full Name:</th>

                                                                <td class="text-muted">{{ $employee->name_en }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="ps-0" scope="row">Email:</th>

                                                                <td class="text-muted">{{ $employee->email }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th class="ps-0" scope="row">Mobile:</th>

                                                                <td class="text-muted">{{ $employee->mobile }}</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th class="ps-0" scope="row">Status:</th>

                                                                @if ($employee->status == 1)
                                                                    <td><span class="badge bg-success">Approved</span></td>
                                                                @elseif ($employee->status == 0)
                                                                    <td><span class="badge bg-primary">Pending</span></td>
                                                                @elseif ($employee->status == 2)
                                                                    <td><span class="badge bg-danger">Declined</span></td>
                                                                @elseif ($employee->status == 4)
                                                                    <td><span class="badge bg-info">Pending Email Verification</span></td>
                                                                @endif
                                                            </tr>

                                                            {{-- <tr>
                                                                <th class="ps-0" scope="row">
                                                                    <ul style="list-style: none; padding-left: 0;">
                                                                        <li class="nav-item">
                                                                            <a onclick="printDiv('overview-tab')" href="#"  class="btn btn-sm btn-success remove_from_print" >
                                                                                <i class="ri-list-unordered d-inline-block"></i> <span class="d-md-inline-block">{{__('pages.Print Document')}}</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </th>

                                                                <td class="text-muted"></td>
                                                            </tr> --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">About</h5>

                                                <div class="row">
                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Date of Birth: </label>

                                                        <p>{{ $employee->userInfo->dob ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Gender: </label>

                                                        <p>{{ $employee->userInfo->gender ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Religion: </label>

                                                        <p>{{ $employee->userInfo->religion ?? '' }}</p>
                                                    </div>

                                                    {{-- <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Birth Certificate:</label>

                                                        <p>{{ $employee->userInfo->birth_certificate_no ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">{{__('pages.NID number')}}:</label>

                                                        <p>{{ $employee->userInfo->nid_no ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">{{__('pages.Passport Number')}}:</label>

                                                        <p>{{ $employee->userInfo->passport_no ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">{{__('pages.Marital Status')}}:</label>

                                                        <p>{{ $employee->userInfo->marital_status ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">{{__('pages.Employee ID')}}:</label>

                                                        <p>{{ $employee->userInfo->employee_id ?? '' }}</p>
                                                    </div> --}}

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Role:</label>

                                                        <p>{{ $employee->role->display_name ?? ''}}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">User Type:</label>

                                                        @if ($employee->user_type == 4)
                                                            <p>User</p>
                                                        @elseif ($employee->user_type == 3)
                                                            <p>Employee</p>
                                                        @elseif ($employee->user_type == 2)
                                                            <p>Admin</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Department:</label>

                                                        <p>{{ $employee->userInfo->department->name ?? ''}}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Designation:</label>

                                                        @if ($employee->user_type == 4)
                                                            <p>{{ $employee->userInfo->designation ?? ''}}</p>
                                                        @else
                                                            <p>{{ $employee->userInfo->post->name ?? ''}}</p>
                                                        @endif
                                                    </div>

                                                    {{-- <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">{{__('pages.Employee Office')}}:</label>

                                                        <p>{{ $employee->userInfo->office->name ?? ''}}</p>
                                                    </div>

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Employee Category:</label>

                                                        <p>{{ $employee->categoryInfo->name ?? ''}}</p>
                                                    </div> --}}

                                                    <div class="col-6 col-md-4">
                                                        <label for="" class="text-dark">Signature:</label>

                                                        @if (($employee->userInfo->signature ?? '') && Storage::exists('public/signature/' . $employee->userInfo->signature))
                                                            <img src="{{ asset('storage/signature/' . ($employee->userInfo->signature ?? ''))}}" alt="Signature" class="img-thumbnail" style="width: 200px;" />
                                                        @else
                                                            <img src="https://png.pngtree.com/png-clipart/20190925/original/pngtree-no-image-vector-illustration-isolated-png-image_4979075.jpg" alt="Signature" class="img-thumbnail" style="width: 200px;" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <!--end row-->
                                            </div>
                                            <!--end card-body-->
                                        </div><!-- end card -->
                                    </div>

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

                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">Address</h5>

                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        <h5>Present Address</h5>

                                                        <label for="" class="text-dark">Division: </label>
                                                        <p>{{ $employee->userAddress->presentDivision->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">District: </label>
                                                        <p>{{ $employee->userAddress->presentDistrictName->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">Thana/Upazila: </label>
                                                        <p>{{ $employee->userAddress->presentUpazila->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">Post Office: </label>
                                                        <p>{{ $employee->userAddress->present_post_office ?? '' }}</p>

                                                        <label for="" class="text-dark">Post Code: </label>
                                                        <p>{{ $employee->userAddress->present_post_code ?? '' }}</p>

                                                        <label for="" class="text-dark">Village/Road: </label>
                                                        <p>{{ $employee->userAddress->present_address ?? '' }}</p>
                                                    </div>

                                                    <div class="col-6 col-md-6">
                                                        <h5>Permanent Address</h5>

                                                        <label for="" class="text-dark">Division: </label>
                                                        <p>{{ $employee->userAddress->permanentDivision->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">District: </label>
                                                        <p>{{ $employee->userAddress->permanentDistrict->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">Thana/Upazila: </label>
                                                        <p>{{ $employee->userAddress->permanentUpazila->name_en ?? '' }}</p>

                                                        <label for="" class="text-dark">Post Office: </label>
                                                        <p>{{ $employee->userAddress->permanent_post_office ?? '' }}</p>

                                                        <label for="" class="text-dark">Post Code: </label>
                                                        <p>{{ $employee->userAddress->permanent_post_code ?? '' }}</p>

                                                        <label for="" class="text-dark">Village/Road: </label>
                                                        <p>{{ $employee->userAddress->permanent_address ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <div class="p-2"><h5 class="card-title mb-3" style="font-size: 1.5em; font-weight: 600;">Educational information</h5></div>

                                                    <div class="p-2">
                                                        <span class="remove_from_print">
                                                            @can('add_educational_info')
                                                                <a href="{{ route('admin.user.add_education', Crypt::encryptString($employee->id)) }}" title="Add Education Info" class="btn btn-sm btn-success">+</a>
                                                            @endcan
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    @foreach ($employee->academicRecordDatas->sortBy('academicExamInfo.sl') as $academicRecord)
                                                        <div class="col-md-6">
                                                            <table class="table table-borderless">
                                                                <thead>
                                                                <tr>
                                                                    <th colspan="2">
                                                                        {{$academicRecord->name_en}}
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if ($academicRecord->roll)
                                                                        <tr>
                                                                            <td>{{__('pages.Roll No')}}</td>
                                                                            <td>
                                                                                {{ $academicRecord->roll ?? ''}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->reg_no)
                                                                        <tr>
                                                                            <td>{{__('pages.Registration No')}}</td>
                                                                            <td>
                                                                                {{$academicRecord->reg_no}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($academicRecord->pass_year)
                                                                        <tr>
                                                                            <td>{{__('pages.Passing year')}}</td>
                                                                            <td>
                                                                                @for ($x = 1950; $x <= date('Y'); $x++)
                                                                                    @if(($academicRecord->pass_year ?? 0) == $x) {{$x}} @endif
                                                                                @endfor
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->institute_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Institute')}}</td>
                                                                            <td>
                                                                                @if ($academicRecord->institute_id > 0)
                                                                                    {{ $academicRecord->institute_name}}
                                                                                @else
                                                                                    {{ $academicRecord->instituteInfo->name_en  ?? ''}}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->institute_name and !$academicRecord->institute_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Institute')}}</td>
                                                                            <td>
                                                                                {{ $academicRecord->institute_name}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->exam_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Exam')}}</td>
                                                                            <td>
                                                                                @if ($academicRecord->exam_id == 0)
                                                                                    {{ $academicRecord->exam_name}}
                                                                                @else
                                                                                    {{ $academicRecord->examInfo->name_en  ?? ''}}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($academicRecord->exam_name and (!$academicRecord->exam_id or $academicRecord->exam_id == 0))
                                                                        <tr>
                                                                            <td>{{__('pages.Exam')}}</td>
                                                                            <td>
                                                                                {{ $academicRecord->exam_name}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif


                                                                    
                                                                    @if ($academicRecord->board_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Board')}}</td>
                                                                            <td>
                                                                                @if ($academicRecord->board_id == 0)
                                                                                    {{ $academicRecord->board_name}}
                                                                                @else
                                                                                    {{ $academicRecord->boardInfo->name_en  ?? ''}}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($academicRecord->board_name and (!$academicRecord->board_id or $academicRecord->board_id == 0))
                                                                        <tr>
                                                                            <td>{{__('pages.Board')}}</td>
                                                                            <td>
                                                                                {{ $academicRecord->board_name}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->subject_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Subject')}}</td>
                                                                            <td>
                                                                                @if ($academicRecord->subject_id == 0)
                                                                                    {{ $academicRecord->subject_name}}
                                                                                @else
                                                                                    {{ $academicRecord->subjectInfo->name_en  ?? ''}}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->result_type)
                                                                        <tr>
                                                                            <td>{{__('pages.Result Type')}}</td>
                                                                            <td>
                                                                                @foreach (result_types() as $index => $result_types)
                                                                                    @if(($academicRecord->result_type ?? 0) == $index) {{$result_types}} @endif
                                                                                @endforeach
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->result_type and in_array($academicRecord->result_type, [5,6]))
                                                                        <tr>
                                                                            <td>{{__('pages.Result')}}</td>
                                                                            <td>
                                                                                {{$academicRecord->result ?? ''}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    @if ($academicRecord->duration_id)
                                                                        <tr>
                                                                            <td>{{__('pages.Duration')}}</td>
                                                                            <td>
                                                                                {{ $academicRecord->durationInfo->name_en  ?? ''}}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    
                                                                    @if ($academicRecord->certificate_file)
                                                                        <tr>
                                                                            <td>Certificate File</td>
                                                                            <td>
                                                                                <a class="btn btn-sm btn-success" target="_blank" href="{{asset('storage/certificate_file')}}/{{ $academicRecord->certificate_file}}">View</a>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div> --}}

                                        @if ($employee->status == 0)
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="hstack gap-2">
                                                                <button type="button" class="btn btn-success" id="approve" onclick="approve({{ $employee->id }})">Approve</button>
                                                                <button type="button" class="btn btn-danger" id="decline" onclick="decline({{ $employee->id }})">Decline</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
       
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

    <script>
        function approve(Id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to approve this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0AB39C',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.approve') }}",
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: Id
                        },
                        beforeSend: function() {
                            $('.btn-danger').prop('disabled', true);
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
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', xhr.responseJSON.message || 'An error occurred.', 'error');
                        },
                        complete: function() {
                            $('.btn-danger').prop('disabled', false);
                        }
                    });
                }
            });
        }

        function decline(Id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to decline this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Decline'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.decline') }}",
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: Id
                        },
                        beforeSend: function() {
                            $('.btn-danger').prop('disabled', true);
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
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', xhr.responseJSON.message || 'An error occurred.', 'error');
                        },
                        complete: function() {
                            $('.btn-danger').prop('disabled', false);
                        }
                    });
                }
            });
        }
    </script>
@endpush