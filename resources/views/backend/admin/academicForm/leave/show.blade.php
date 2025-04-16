@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Leave Details'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Leave Details')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('pages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Leave Details')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Leave Details')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">

                                <input type="hidden" name="office_id" id="office_id">

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="employee_name" class="form-label">{{__('pages.Employee Name')}}</label>
                                        <input type="text" class="form-control" name="employee_name" id="employee_name" value="{{$leave->employeeName->name_en ?? '-'}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="Office" class="form-label">{{__('pages.Office')}}</label>
                                        <input type="text" class="form-control" name="Office" id="Office" value="{{$leave->officeName->name ?? '-'}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="Leave_Category" class="form-label">{{__('pages.Leave Category')}}</label>
                                        <input type="text" class="form-control" name="Leave_Category" id="Leave_Category" value="{{$leave->leaveCategoryName->name_en ?? '-'}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="Leave_Title" class="form-label">{{__('pages.Leave Title')}}</label>
                                        <input type="text" class="form-control" name="Leave_Title" id="Leave_Title" value="{{$leave->title ?? '-'}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="start" class="form-label">{{__('pages.Start Date')}}</label>
                                        <input type="text" class="form-control" name="start" id="start" value="{{date('d-m-Y', strtotime($leave->start))}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="End" class="form-label">{{__('pages.End Date')}}</label>
                                        <input type="text" class="form-control" name="End" id="End" value="{{date('d-m-Y', strtotime($leave->end))}}" disabled>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div>
                                        <label for="leave_reason" class="form-label">{{__('pages.Leave Reason')}}</label>
                                        <textarea class="form-control" rows="3" name="leave_reason" id="leave_reason" disabled>{{$leave->reason}}</textarea>
                                    </div>
                                </div>

                                @if (($leave->updated_by != '') && ($leave->updated_by != NULL))
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="Updated_by" class="form-label">{{__('pages.Updated By')}}</label>
                                            <input type="text" class="form-control" name="Updated_by" id="Updated_by" value="{{$leave->updatedBy->name_en ?? '-'}}" disabled>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="End" class="form-label">{{__('pages.Created By')}}</label>
                                            <input type="text" class="form-control" name="End" id="End" value="{{$leave->createdBy->name_en ?? '-'}}" disabled>
                                        </div>
                                    </div>
                                @endif

                                @if(($leave->documents != '') && ($leave->documents != NULL))
                                    <div class="col-md-4 col-sm-6 col-xsm-12">
                                        <div>
                                            <label for="Updated_by" class="form-label">{{__('pages.Uploaded Document')}}</label>
                                            <a class="form-control" target="_blank" href="{{url('storage/leaveDocuments/').'/'.$leave->documents}}"><i class="las la-file" style="font-size: 1.6em;"></i>{{$leave->documents}}</a>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" @if($leave->status == 1) checked @endif disabled>
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
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#user_id").change(function(){
                let eoffice = ($(this).find(':selected').data('eoffice'));
                $("#office_id").val(eoffice);
            });
        });
    </script>

@endpush