@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Exam Category List'))
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{__('pages.Exam Category List')}}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{__('pages.Exam Category List')}}</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Exam Category List')}}</h4>
                        <div class="flex-shrink-0">
                            @can('add_exam_category')
                                <a href="{{route('admin.examCategory.create')}}" class="btn btn-primary">{{__('pages.Add New Exam Category')}}</a>
                            @endcan

                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">

                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif type="text" class="form-control search" name="name" placeholder="{{__('pages.Exam Category Name')}}">
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
                                        <a style="max-width: 150px;" href="{{route('admin.examCategory.index')}}" class="btn btn-danger w-100"> 
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
                                        <th>{{__('pages.Exam Category Name')}}</th>
                                        <th>{{__('pages.Exam')}}</th>
                                        <th class="text-center">{{__('pages.Status')}}</th>
                                        <th class="text-center">{{__('pages.Action')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($examCategories->count() > 0)
                                        @php
                                            $i = ($examCategories->perPage() * ($examCategories->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($examCategories as $examCat)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$examCat->name_en ?? '-'}}</td>
                                                @php
                                                    $explodedExams = explode(",", $examCat->exam_ids);
                                                @endphp
                                                <td>
                                                    @foreach ($explodedExams as $exmId)
                                                        {{$examCat->examName($exmId)}};
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @if ($examCat->status == 0)
                                                        <span class="badge bg-danger">{{__('pages.Inactive')}}</span>
                                                    @elseif($examCat->status == 1)
                                                        <span class="badge bg-success">{{__('pages.Active')}}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">

                                                    @can('edit_exam_category')
                                                        <a href="{{route('admin.examCategory.edit', $examCat->id)}}" title="Edit" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('delete_exam_category')
                                                        <a onclick="return confirm('Are you sure ?')" href="{{route('admin.examCategory.delete', $examCat->id)}}" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
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
                                {{$examCategories->appends($_GET)->links()}}
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