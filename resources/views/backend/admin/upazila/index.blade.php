
@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Upazila List'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Upazila List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Upazila List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Upazila List')}}</h4>
                        <div class="flex-shrink-0">
                            @can('add_upazila')
                                <a href="{{route('admin.upazila.create')}}" class="btn btn-primary">{{__('pages.Add New Upazila')}}</a>
                            @endcan

                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-2">
                                    <div>
                                        <select class="form-control" data-choices data-choices-search-false name="division" id="idStatus">
                                            <option value="">{{__('pages.Select Division')}}</option>
                                            @foreach ($regions as $region)
                                                    <option @if(isset($_GET['division']) and $_GET['division']==$region->id) selected @endif value="{{$region->id}}">{{$region->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input type="text" class="form-control search" name="name" placeholder="{{__('pages.Upazila Name')}}" @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif>
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                            {{__('pages.Filter')}}
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" href="{{route('admin.upazila.index')}}" class="btn btn-danger w-100"> <i class="ri-restart-line me-1 align-bottom"></i>
                                            {{__('pages.Reset')}}
                                        </a>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{__('pages.No')}}</th>
                                        <th>{{__('pages.Upazila Name')}}</th>
                                        <th>{{__('pages.Division')}}</th>
                                        <th>{{__('pages.District')}}</th>
                                        <th class="text-center">{{__('pages.Status')}}</th>
                                        <th>{{__('pages.Created By')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($upazilas->count() > 0)
                                        @php
                                            $i = ($upazilas->perPage() * ($upazilas->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($upazilas as $upazila)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$upazila->name ?? '-'}}</td>
                                                <td>{{$upazila->districtInfo->divisionInfo->name ?? '-'}}</td>
                                                <td>{{$upazila->districtInfo->name ?? '-'}}</td>
                                                <td class="text-center">
                                                    @if ($upazila->status == 1)
                                                        <span class="badge bg-success">{{__('pages.Active')}}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{__('pages.Inactive')}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$upazila->createdBy->full_name ?? '-'}}</td>
                                                <td class="text-center">

                                                    @can('view_upazila')
                                                        <a href="{{route('admin.upazila.view',$upazila->id)}}" title="View " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('edit_upazila')
                                                        <a href="{{route('admin.upazila.edit',$upazila->id)}}" title="Edit " type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('delete_upazila')
                                                        <a onclick="return confirm('Are you sure ?')" href="{{route('admin.upazila.delete',$upazila->id)}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
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
                                {{$upazilas->appends($_GET)->links()}}
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

