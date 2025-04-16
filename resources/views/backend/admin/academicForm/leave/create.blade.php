@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Add New Leave'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Add New Leave')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Add New Leave')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Add New Leave')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    

                    <div class="card-body">
                        <form action="{{route('admin.leave.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">

                                <input type="hidden" name="office_id" id="office_id">

                                

                                @can('add_personal_leave')
                                    @if ((auth()->user()->role_id) == 1)
                                        <div class="col-md-4 col-sm-6 col-xsm-12">
                                            <div>
                                                <label for="user_id" class="form-label">{{__('messages.Select Employee')}}<span style="color:red;">*</span></label>
                                                <select class="form-control select2" name="user_id" id="user_id" required>
                                                    <option value="">--{{__('messages.Select Employee')}}--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}" data-eoffice="{{$user->userInfo->office_id ?? '-'}}">{{$user->name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                    @endif
                                        
                                @else
                                    @can('add_leave')
                                        <div class="col-md-4 col-sm-6 col-xsm-12">
                                            <div>
                                                <label for="user_id" class="form-label">{{__('messages.Select Employee')}}<span style="color:red;">*</span></label>
                                                <select class="form-control select2" name="user_id" id="user_id" required>
                                                    <option value="">--{{__('messages.Select Employee')}}--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}" data-eoffice="{{$user->userInfo->office_id ?? '-'}}">{{$user->name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endcan
                                @endcan

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="leave_category_id" class="form-label">{{__('pages.Select Leave Category')}}<span style="color:red;">*</span></label>
                                        <select class="form-control select2" name="leave_category_id" id="leave_category_id" required>
                                            <option value="">--{{__('pages.Select Leave Category')}}--</option>
                                            @foreach ($leaveCategories as $leaveCategory)
                                                <option value="{{$leaveCategory->id}}">{{$leaveCategory->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="title" class="form-label">{{__('pages.Leave Title')}}<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter Leave Title" value="{{old('title')}}" required>
                                    </div>
                                </div>

                                <div class="col-md-8 col-sm-12">
                                    <div>
                                        <label for="reason" class="form-label">{{__('pages.Leave Reason')}}<span style="color:red;">*</span></label>
                                        <textarea class="form-control" name="reason" id="reason" rows="5" required>{{old('reason')}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="start" class="form-label">{{__('pages.Start Date')}}<span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" name="start" id="start" placeholder="Leave start date" value="{{old('start')}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="end" class="form-label">{{__('pages.End Date')}}<span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" name="end" id="end" placeholder="Leave end date" value="{{old('end')}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="documents" class="form-label">{{__('pages.Upload File')}}</label>
                                        <input type="file" class="form-control" name="documents" id="documents" placeholder="Leave end date">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>
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

    <script>
        $(document).ready(function() {
            $("#user_id").change(function(){
                let eoffice = ($(this).find(':selected').data('eoffice'));
                $("#office_id").val(eoffice);
            });
        });
    </script>

@endpush