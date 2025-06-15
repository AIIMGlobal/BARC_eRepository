@extends('backend.layouts.app')

@section('title', 'Division List | ' . ($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">{{ __('pages.Division List') }} (Location)</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('messages.Dashboard') }}</a></li>

                                <li class="breadcrumb-item active">{{ __('pages.Division List') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ __('pages.Division List') }} (Location)</h4>

                            <div class="flex-shrink-0">
                                @can('add_region')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                        Add New Division (Location)
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>{{ __('pages.Division Name') }}</th>
                                            <th class="text-center">{{ __('pages.Status') }}</th>
                                            {{-- <th>{{__('pages.Created By')}}</th> --}}
                                            <th class="text-center">{{ __('pages.Action') }} </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($regions->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($regions as $region)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>

                                                    <td>{{ $region->name_en ?? '-' }}</td>

                                                    <td class="text-center">
                                                        @if ($region->status == 1)
                                                            <span class="badge bg-success">{{ __('pages.Active') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('pages.Inactive') }}</span>
                                                        @endif
                                                    </td>

                                                    {{-- <td>{{$region->createdBy->name_en ?? '-'}}</td> --}}

                                                    <td class="text-center">
                                                        @can('view_region')
                                                            <a href="{{ route('admin.region.view', $region->id) }}" title="View " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_region')
                                                            <button title="Edit" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editDivision{{$region->id}}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_region')
                                                            <a onclick="return confirm('Are you sure?')" href="{{route('admin.region.delete',$region->id)}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="editDivision{{$region->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">{{ __('pages.Edit Division') }} (Location)</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{route('admin.region.update', $region->id)}}" method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="name_en" class="form-label">{{ __('pages.Division Name') }}<span style="color:red;">*</span></label>

                                                                                <input type="text" class="form-control" name="name_en" placeholder=" Division Name" value="{{ $region->name_en }}" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                                                <input @if($region->status == 1) checked @endif class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1">

                                                                                <label class="form-check-label" for="SwitchCheck11">{{ __('pages.Status') }}</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('pages.Cancel') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('pages.Update') }}</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%" class="text-center"><b>{{ __('pages.No Data Found') }}</b></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <!-- end tbody -->
                                </table>
                                <!-- end table -->

                                <div class="mt-3">
                                    {{ $regions->appends($_GET)->links() }}
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

    <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Add New Division (Location)</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.region.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <label for="name_en" class="form-label">{{ __('pages.Division Name') }} <span style="color:red;">*</span></label>

                                    <input type="text" class="form-control" name="name_en" placeholder="Enter Division Name" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>

                                    <label class="form-check-label" for="SwitchCheck11">{{ __('pages.Status') }} </label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('pages.Cancel') }}</button>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection