@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('menu.Archive List'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Archive List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Archive List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Archive List')}}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif type="text" class="form-control search" name="name" placeholder="{{__('pages.User Name/Mobile/E-mail')}}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>{{__('pages.Filter')}}
                                        </button>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" href="{{route('admin.user.archive_list')}}" class="btn btn-danger w-100"> 
                                            <i class="ri-restart-line me-1 align-bottom"></i>{{__('pages.Reset')}}
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{__('pages.No')}}</th>
                                        <th>{{__('pages.Name')}}</th>
                                        <th>{{__('pages.Email')}}</th>
                                        <th class="text-center">{{__('pages.Mobile')}}</th>
                                        <th>{{__('pages.Role')}}</th>
                                        <th class="text-center">{{__('pages.Status')}}</th>
                                        <th>{{__('pages.Office')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($employees->count() > 0)
                                        @php
                                            $i = ($employees->perPage() * ($employees->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$employee->name_en ?? '-'}}</td>
                                                <td>{{$employee->email ?? '-'}}</td>
                                                <td class="text-center">{{$employee->mobile ?? '-'}}</td>
                                                <td>{{$employee->role ? $employee->role->display_name : '-'}}</td>
                                                <td class="text-center">
                                                    @if ($employee->status == 1)
                                                        <span class="badge bg-success">{{__('pages.Active')}}</span>
                                                    @elseif($employee->status == 2)
                                                        <span class="badge bg-warning">{{__('pages.Archived')}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$employee->officeName($employee->userInfo->office_id)}}</td>
                                                <td class="text-center">

                                                    @can('view_user')
                                                        <a href="{{route('admin.user.show', Crypt::encryptString($employee->id) )}}" title="Show" class="btn btn-sm btn-info btn-icon waves-effect waves-light">
                                                            <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    {{-- @can('edit_user')
                                                        <a href="{{route('admin.user.edit', Crypt::encryptString($employee->id))}}" title="Edit" class="btn btn-sm btn-primary btn-icon waves-effect waves-light">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @elsecan('edit_user_minimum')
                                                        <a href="{{route('admin.user.editUserMinimum', $employee->id)}}" title="Edit" class="btn btn-sm btn-primary btn-icon waves-effect waves-light">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan --}}

                                                    @can('block_user')
                                                        @if ($employee->status == 1)
                                                            <a onclick="return confirm('Are you sure, you want to Archive the user?')" href="{{route('admin.user.block', Crypt::encryptString($employee->id))}}" title="Archive" class="btn btn-sm btn-warning btn-icon waves-effect waves-light">
                                                                <i class="las la-lock" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @else
                                                            <a onclick="return confirm('Are you sure, you want to Un-Archive the user?')" href="{{route('admin.user.active', Crypt::encryptString($employee->id))}}" title="Un-Archive" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                                                <i class="las la-lock-open" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="100%" class="text-center"><b>{{__('pages.No Data Found')}}</b></td>
                                        </tr>
                                    @endif

                                </tbody>
                                <!-- end tbody -->
                            </table>
                            <!-- end table -->
                        </div>
                        <!-- end table responsive -->
                    </div>
                    <!-- end card body -->
                    <div class="card-footer">
                        {{$employees->appends($_GET)->links()}}
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

