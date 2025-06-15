@extends('backend.layouts.app')

@section('title', 'District List | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">{{ __('pages.District List') }}</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('messages.Dashboard') }}</a></li>

                                <li class="breadcrumb-item active">{{ __('pages.District List') }}</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">{{ __('pages.District List') }}</h4>
                            
                            <div class="flex-shrink-0">
                                @can('add_district')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                        {{ __('pages.Add New District') }}
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-2">
                                        <div>
                                            <select class="form-control select2" name="division" id="idStatus">
                                                <option value="">Search by Division (Location)</option>

                                                @foreach ($regions as $region)
                                                    <option @if(isset($_GET['division']) and $_GET['division'] == $region->id) selected @endif value="{{ $region->id }}">{{ $region->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['name_en']) and $_GET['name_en']!='') value="{{$_GET['name_en']}}" @endif type="text" class="form-control search" name="name_en" placeholder="{{ __('pages.District Name') }}">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                                {{ __('pages.Filter') }}
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{route('admin.district.index') }}" class="btn btn-danger w-100"> <i class="ri-restart-line me-1 align-bottom"></i>
                                                {{ __('pages.Reset') }}
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
                                            <th class="text-center">#</th>
                                            <th>{{ __('pages.District Name') }}</th>
                                            <th>{{ __('pages.Division') }}</th>
                                            <th class="text-center">{{ __('pages.Status') }}</th>
                                            {{-- <th>{{ __('pages.Created By') }}</th> --}}
                                            <th class="text-center">{{ __('pages.Action') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($districts->count() > 0)
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach ($districts as $district)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>

                                                    <td>{{ $district->name_en ?? '-' }}</td>

                                                    <td>{{ $district->divisionInfo->name_en ?? '-' }}</td>

                                                    <td class="text-center">
                                                        @if ($district->status == 1)
                                                            <span class="badge bg-success">{{ __('pages.Active') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('pages.Inactive') }}</span>
                                                        @endif
                                                    </td>

                                                    {{-- <td>{{$district->createdBy->name_en ?? '-'}}</td> --}}

                                                    <td class="text-center">
                                                        @can('view_district')
                                                            <a href="{{ route('admin.district.view', $district->id) }}" title="View " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @can('edit_district')
                                                            <button title="Edit" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editDistrict{{ $district->id }}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_district')
                                                            <a onclick="return confirm('Are you sure ?')" href="{{ route('admin.district.delete', $district->id) }}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                <div class="modal fade" id="editDistrict{{ $district->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">{{ __('pages.Edit District') }}</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{route('admin.district.update', $district->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="name" class="form-label">{{ __('pages.District Name') }} <span style="color:red;">*</span></label>

                                                                                <input type="text" class="form-control" name="name" placeholder=" District Name" value="{{ $district->name_en }}" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div>
                                                                                <label for="region" class="form-label">{{ __('pages.Select Division') }} (Location): <span style="color:red;">*</span></label>

                                                                                <select id="my-select" class="form-control select2" name="division_id" required>
                                                                                    <option value="">--{{ __('pages.Select Division') }} (Location)--</option>

                                                                                    @foreach ($regions as $region)
                                                                                        <option @if($region->id == $district->division_id) selected @endif value="{{ $region->id }}">{{ $region->name_en }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-lg-12">
                                                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                                                <input @if($district->status == 1) checked @endif class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1">
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
                                    {{ $districts->appends($_GET)->links() }}
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
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('pages.Add New District') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.district.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <label for="name" class="form-label">{{ __('pages.District Name') }} <span style="color:red;">*</span></label>

                                    <input type="text" class="form-control" name="name" placeholder=" {{ __('pages.District Name') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div>
                                    <label for="region" class="form-label">{{ __('pages.Select Division') }} (Location): <span style="color:red;">*</span></label>

                                    <select id="my-select" class="form-control select2" name="division_id" required>
                                        <option value="">--{{ __('pages.Select Division') }} (Location)--</option>

                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" checked>

                                    <label class="form-check-label" for="SwitchCheck11">{{ __('pages.Status') }}</label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('pages.Cancel') }}</button>

                                    <button type="submit" class="btn btn-primary">{{ __('pages.Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection