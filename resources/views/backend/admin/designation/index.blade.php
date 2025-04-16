@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Designation List'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Designation List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Designation List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Designation List')}}</h4>
                        <div class="flex-shrink-0">
                            @can('add_designation')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewDesignation">
                                    {{__('pages.Add New Designation')}}
                                </button>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif type="text" class="form-control search" name="name" placeholder="{{__('pages.Designation Name')}}">
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
                                        <a style="max-width: 150px;" href="{{route('admin.designation.index')}}" class="btn btn-danger w-100"> 
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
                                        <th>{{__('pages.Designation')}}</th>
                                        <th>{{__('pages.Created By')}}</th>
                                        <th>{{__('pages.Created At')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($designations->count() > 0)
                                        @php
                                            $i = ($designations->perPage() * ($designations->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($designations as $designation)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$designation->name ?? '-'}}</td>
                                                <td>{{$designation->createdUser ? $designation->createdUser->full_name : '-'}}</td>
                                                <td>
                                                    {{$designation->created_at->format('d-m-Y')}}
                                                </td>
                                                <td class="text-center">
                                                    @can('edit_designation')
                                                        <button type="button" title="Edit" class="btn btn-icon btn-sm btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editdesignation{{$designation->id}}">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </button>
                                                    @endcan
                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                            @endphp



                                            <div class="modal fade" id="editdesignation{{$designation->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalgridLabel">{{__('pages.Edit Designation')}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('admin.designation.update', $designation->id)}}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row g-3">
                                                                    <div class="col-lg-12">
                                                                        <div>
                                                                            <label for="lastName" class="form-label">{{__('pages.Designation')}} <span style="color:red;">*</span></label>
                                                                            <input type="text" class="form-control" name="name" value="{{$designation->name}}" required>
                                                                        </div>
                                                                    </div><!--end col-->
                                                                    <div class="col-lg-12">
                                                                        <div class="hstack gap-2 justify-content-end">
                                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('pages.Cancel')}}</button>
                                                                            <button type="submit" class="btn btn-primary">{{__('pages.Update')}}</button>
                                                                        </div>
                                                                    </div><!--end col-->
                                                                </div><!--end row-->
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




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
                        {{$designations->links()}}
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





<!-- Grids in modals -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
    Launch Demo Modal
</button> --}}
<div class="modal fade" id="addNewDesignation" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">{{__('pages.Add New Designation')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.designation.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div>
                                <label for="lastName" class="form-label">{{__('pages.Designation')}}<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder=" new designation" required>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('pages.Cancel')}}</button>
                                <button type="submit" class="btn btn-primary">{{__('pages.Submit')}}</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
