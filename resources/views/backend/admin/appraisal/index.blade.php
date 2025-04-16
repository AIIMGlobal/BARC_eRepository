@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | Appraisal List')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Appraisal List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Appraisal List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Appraisal List')}}</h4>
                        <div class="flex-shrink-0">
                            @if (auth()->user()->role_id == 1)
                                @can('add_appraisal')
                                    <a href="{{route('admin.appraisal.create')}}" class="btn btn-primary">{{__('pages.Add New Appraisal')}}</a>
                                @endcan
                            @else
                                @can('add_personal_appraisal')
                                    <a href="{{route('admin.appraisal.create')}}?personal=1" class="btn btn-primary">{{__('pages.Add New Appraisal')}}</a>
                                @else
                                    @can('add_appraisal')
                                        <a href="{{route('admin.appraisal.create')}}" class="btn btn-primary">{{__('pages.Add New Appraisal')}}</a>
                                    @endcan
                                @endcan
                            @endif

                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif type="text" class="form-control search" name="name" placeholder="Employee Name">
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
                                        <a style="max-width: 150px;" href="{{route('admin.appraisal.index')}}" class="btn btn-danger w-100"> 
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
                                        <th>{{__('pages.Employee')}}</th>
                                        <th>{{__('pages.Post')}}</th>
                                        <th>{{__('pages.From Date')}}</th>
                                        <th>{{__('pages.To Date')}}</th>
                                        <th class="text-center">{{__('pages.Status')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($appraisals->count() > 0)
                                        @php
                                            $i = ($appraisals->perPage() * ($appraisals->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($appraisals as $appraisal)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$appraisal->employeeInfo->name_en ?? '-'}}</td>
                                                <td>{{$appraisal->postInfo->name_en ?? '-'}}</td>
                                                <td>{{$appraisal->start}}</td>
                                                <td>{{$appraisal->end ?? 'Continuing'}}</td>
                                                <td class="text-center">
                                                    @if ($appraisal->status == 0)
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @elseif($appraisal->status == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">

                                                    @can('view_appraisal')
                                                        <a title="View Appraisal History" class="btn btn-success btn-sm btn-icon waves-effect waves-light" href="{{route('admin.appraisal.view_history', Crypt::encryptString($appraisal->user_id))}}">
                                                            <i class="las la-list-ul" style="font-size: 1.6em;"></i>
                                                        </a>
                                                        <a title="View" class="btn btn-success btn-sm btn-icon waves-effect waves-light" href="{{route('admin.appraisal.view', Crypt::encryptString($appraisal->id))}}">
                                                            <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('edit_appraisal')
                                                        <a title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light" href="{{route('admin.appraisal.edit', Crypt::encryptString($appraisal->id))}}">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('delete_appraisal')
                                                        <a onclick="return confirm('Are you sure ?')" href="{{route('admin.appraisal.delete', Crypt::encryptString($appraisal->id))}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                        </a>
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
                            <div class="mt-3">
                                {{$appraisals->appends($_GET)->links()}}
                            </div>
                        </div>
                        <!-- end table responsive -->
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
    {{-- <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script> --}}
@endpush