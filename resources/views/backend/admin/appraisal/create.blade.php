@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | Add New Appraisal')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Add New Appraisal')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Add New Appraisal')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Add New Appraisal')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.appraisal.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                @can('add_personal_appraisal')
                                    @if (auth()->user()->role_id == 1)
                                        <div class="col-md-4 col-sm-6 col-xsm-12">
                                            <div>
                                                <label for="user_id" class="form-label">{{__('messages.Select Employee')}}<span style="color:red;">*</span></label>
                                                <select class="form-control select2" name="user_id" id="user_id" required>
                                                    <option value="">--{{__('messages.Select Employee')}}--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                    @endif
                                    
                                @else
                                    @can('add_appraisal')
                                        <div class="col-md-4 col-sm-6 col-xsm-12">
                                            <div>
                                                <label for="user_id" class="form-label">{{__('messages.Select Employee')}}<span style="color:red;">*</span></label>
                                                <select class="form-control select2" name="user_id" id="user_id" required>
                                                    <option value="">--Select Employee--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endcan
                                @endcan
                                
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="post_id" class="form-label">{{__('pages.Employee Post')}}<span style="color:red;">*</span></label>
                                        <select class="form-control select2" name="post_id" id="post_id" required>
                                            <option value="">--Select Post--</option>
                                            @foreach ($posts as $post)
                                                <option value="{{$post->id}}">{{$post->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="name_en" class="form-label">{{__('pages.Appraisal Start Date')}}<span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" name="start" id="start" placeholder="Date" value="{{old('start')}}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="name_en" class="form-label">{{__('pages.Appraisal End Date')}} ({{__('pages.Keep blank if continue')}})</label>
                                        <input type="date" class="form-control" name="end" id="end" placeholder="Date" value="{{old('end')}}">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="basic_salary" class="form-label">{{__('pages.Basic Salary')}}</label>
                                        <input type="number" class="form-control" name="basic_salary" id="basic_salary" placeholder="Enter Your Basic Salary Amount" value="{{old('basic_salary')}}" >
                                    </div>
                                </div>

                                

                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="description" class="form-label">{{__('pages.Comment')}}</label>
                                        
                                        <textarea class="form-control" placeholder="Enter comments" name="comments"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xsm-12" style="margin-top: 3%">
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                        <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                <label class="form-check-label form-label" for="SwitchCheck_5">{{__('pages.Want to upload appriasal document')}}</label>
                                                <input class="form-check-input form-control" type="checkbox" role="switch" name="have_document" id="SwitchCheck_5" value="1" autocomplete="off">
                                            </div>
        
                                            <div class="hidden_area d-none" style="max-width: 500px;">
                                                <table class="table table-bordered table-sm text-left table-area">
                                                    <thead>
                                                        <tr>
                                                        <th>{{__('pages.File Title')}} <span style="color:red;">*</span></th>
                                                        <th>{{__('pages.Documents')}} <span style="color:red;">*</span></th>
                                                        <th><span style="cursor: pointer;" class="btn btn-info btn-sm font-weight-bolder font-size-sm mr-3 add_more">{{__('pages.Add more document')}}</span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <tr>
                                                        <td>
                                                            <input type="text" name="file_title[]" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control" name="file[]">
                                                        </td>
                                                        <td>
                                                            <span onclick="remove_tr(this)" style="cursor: pointer;" class="btn btn-danger font-weight-bolder font-size-sm mr-3 remove_more">{{__('pages.Remove')}}</span>
                                                        </td>
                                                        </tr>
    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                <label class="form-check-label form-label" for="SwitchCheck_9">{{__('pages.Transfer Applied')}}?</label>
                                                <input class="form-check-input form-control" type="checkbox" role="switch" name="transfer_applied" id="SwitchCheck_9" value="1" autocomplete="off">
                                            </div>
                                            <div style="max-width:400px" class="transfer_applied d-none">
                                                <label for="transfer_id" class="form-label">{{__('pages.Select Office')}}<span style="color:red;">*</span></label>
                                                <select class="form-control select2" name="transfer_id" id="transfer_id">
                                                    <option value="">--Select Office--</option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{$office->id}}">{{$office->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{__('pages.Submit')}}</button>
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
    

        $('#SwitchCheck_9').click(function() {
            
            var checked = this.checked;
            if (checked) {
                $('.transfer_applied').removeClass('d-none');
                $('.transfer_id').attr('required','required');

            } else {
                $('.transfer_applied').addClass('d-none');
                $('.transfer_id').removeAttr('required');
            }
        });
        
        $('#SwitchCheck_5').click(function() {
            
            var checked = this.checked;
            if (checked) {
                $('.hidden_area').removeClass('d-none');
                $('.table-area').find('input').attr('required','required');

            } else {
                $('.hidden_area').addClass('d-none');
                $('.table-area').find('input').removeAttr('required');
            }
        });

        $('.add_more').click(function() {
            
            var clone = $(".table-area tbody tr:first-child").clone();
            $(".table-area tbody").append(clone);
            $(".table-area tbody tr:last-child td").find('input').val('');
        });

        function remove_tr(that) {
            $(that).closest('tr').remove();
        }
    </script>
    


@endpush

@push('css')
    <style>
        .table-area tbody tr:first-child .remove_more {
            display: none;
        }
    </style>
@endpush