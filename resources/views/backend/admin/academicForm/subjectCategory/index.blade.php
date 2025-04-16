@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Subject Category List'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Subject Category List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Subject Category List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Subject Category List')}}</h4>
                        <div class="flex-shrink-0">
                            @can('add_subject_category')
                                <a href="{{route('admin.subjectCategory.create')}}" class="btn btn-primary">{{__('pages.Add New Subject Category')}}</a>
                            @endcan

                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif type="text" class="form-control search" name="name" placeholder="{{__('pages.Subject Category Name')}}">
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
                                        <a style="max-width: 150px;" href="{{route('admin.subjectCategory.index')}}" class="btn btn-danger w-100"> 
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
                                        <th>{{__('pages.Subject Category Name')}}</th>
                                        <th>{{__('pages.Subjects')}}</th>
                                        <th class="text-center">{{__('pages.Status')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($subjectCategories->count() > 0)
                                        @php
                                            $i = ($subjectCategories->perPage() * ($subjectCategories->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($subjectCategories as $subjectCat)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$subjectCat->name_en ?? '-'}}</td>
                                                @php
                                                    $explodedSubjects = explode(",", $subjectCat->subject_ids);
                                                @endphp
                                                <td>
                                                    @foreach ($explodedSubjects as $subId)
                                                        {{$subjectCat->subjectName($subId)}};
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @if ($subjectCat->status == 0)
                                                        <span class="badge bg-danger">{{__('pages.Inactive')}}</span>
                                                    @elseif($subjectCat->status == 1)
                                                        <span class="badge bg-success">{{__('pages.Active')}}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">

                                                    @can('edit_subject_category')
                                                        <button type="button" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editsubjectCategory{{$subjectCat->id}}">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </button>
                                                    @endcan

                                                    @can('delete_subject_category')
                                                        <a onclick="return confirm('Are you sure ?')" href="{{route('admin.subjectCategory.delete', $subjectCat->id)}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                            @endphp




                                            <div class="modal fade" id="editsubjectCategory{{$subjectCat->id}}" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalgridLabel">{{__('pages.Update Subject Category')}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('admin.subjectCategory.update', $subjectCat->id)}}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row g-3">

                                                                    <div class="col-12">
                                                                        <div>
                                                                            <label for="name_en" class="form-label">{{__('pages.Subject Category Name')}}<span style="color:red;">*</span></label>
                                                                            <input type="text" class="form-control" name="name_en" id="name_en" placeholder="Enter Exam Category Name" value="{{$subjectCat->name_en}}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        
                                                                        <div>
                                                                            <label for="exam_ids" class="form-label">{{__('pages.Select Subjects')}}<span style="color:red;">*</span></label>
                                                                            <select class="form-control select2" name="subject_ids[]" id="subject_ids" multiple required>
                                                                                <option value="">--{{__('pages.Select Subjects')}}--</option>
                                                                                @foreach ($subjects as $subject)
                                                                                    <option value="{{$subject->id}}" {{ (in_array($subject->id, $explodedSubjects)) ? 'selected' : '' }}>{{$subject->name_en}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                                            <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>
                                                                            <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" @if($subjectCat->status == 1) checked @endif>
                                                                        </div>
                                                                    </div>

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
                            <div class="mt-3">
                                {{$subjectCategories->appends($_GET)->links()}}
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
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush