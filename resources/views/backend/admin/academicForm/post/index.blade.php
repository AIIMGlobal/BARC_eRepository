@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | '.__('pages.Post List'))

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{__('pages.Post List')}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                                <li class="breadcrumb-item active">{{__('pages.Post List')}}</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">{{__('pages.Post List')}}</h4>

                            <div class="flex-shrink-0">
                                @can('add_post')
                                    <a href="{{ route('admin.post.create') }}" class="btn btn-primary">{{__('pages.Add New Post')}}</a>
                                @endcan
                            </div>
                        </div>
                        
                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if (isset($_GET['searchText']) && $_GET['searchText'] != '') value="{{ $_GET['searchText'] }}" @endif type="text" class="form-control search" name="searchText" placeholder="{{__('pages.Post Title')}}">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="search_post_category_id" id="search_post_category_id" class="form-control search_post_category_id"> 
                                                <option value="">--Search By Post Category--</option>

                                                @foreach ($post_categorys as $post_category)
                                                    <option value="{{ $post_category->id }}" @if (isset($_GET['search_post_category_id']) && ($_GET['search_post_category_id'] != '') && ($_GET['search_post_category_id'] == $post_category->id)) selected @endif>{{ $post_category->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>{{__('pages.Filter')}}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{ route('admin.post.index') }}" class="btn btn-danger w-100"> 
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
                                            <th>{{__('pages.Post Title')}}</th>
                                            <th>{{__('pages.Post Category')}}</th>
                                            <th>{{__('pages.Grade')}}</th>
                                            <th class="text-center">{{__('pages.Status')}}</th>
                                            <th class="text-center">{{__('pages.Action')}}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($posts->count() > 0)
                                            @php
                                                $i = ($posts->perPage() * ($posts->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($posts as $post)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>

                                                    <td>{{ $post->name_en ?? '-' }}</td>

                                                    <td>{{ $post->postCategory->name_en ?? '-' }}</td>

                                                    <td>{{ $post->grade->name_en ?? '-' }}</td>

                                                    <td class="text-center">
                                                        @if ($post->status == 0)
                                                            <span class="badge bg-danger">{{__('pages.Inactive')}}</span>
                                                        @elseif($post->status == 1)
                                                            <span class="badge bg-success">{{__('pages.Active')}}</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @can('show_post')
                                                            <button type="button" title="Detail" class="btn btn-primary btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#showSalaryScale{{ $post->id }}">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('edit_post')
                                                            <button type="button" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editSalaryScale{{ $post->id }}">
                                                                <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan

                                                        @can('delete_post')
                                                            <button title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light delete" data-id="{{ $post->id }}">
                                                                <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                            </button>
                                                        @endcan
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp

                                                {{-- detail modal --}}
                                                <div class="modal fade" id="showSalaryScale{{ $post->id }}" tabindex="-1" aria-labelledby="postpleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="postpleModalgridLabel">{{__('pages.Post Details')}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="row g-3">
                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Post Title')}}</label>

                                                                            <input type="text" class="form-control" value="{{ $post->name_en }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Post Category')}}</label>

                                                                            <input type="text" class="form-control" value="{{ $post->postCategory->name_en ?? '-' }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Grade')}}</label>

                                                                            <input type="text" class="form-control" value="{{ $post->grade->name_en ?? '-' }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Created By')}}</label>

                                                                            <input type="text" class="form-control" value="{{ $post->createdBy->name_en ?? '-' }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    @if ($post->updated_by)
                                                                        <div class="col-md-12">
                                                                            <div>
                                                                                <label for="" class="form-label">{{__('pages.Updated By')}}</label>

                                                                                <input type="text" class="form-control" value="{{ $post->updatedBy->name_en ?? '-' }}" disabled>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Created At')}}</label>

                                                                            <input type="text" class="form-control" value="{{ date('M d, Y', strtotime($post->created_at)) }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Updated At')}}</label>

                                                                            <input type="text" class="form-control" value="{{ date('M d, Y', strtotime($post->updated_at)) }}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <label for="" class="form-label">{{__('pages.Status')}}</label>

                                                                            <input type="text" class="form-control {{ $post->status == 1 ? 'text-success' : 'text-danger' }}" value="{{ $post->status == 1 ? 'Active' : 'Inactive' }}" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div><!--end row-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- edit modal --}}
                                                <div class="modal fade" id="editSalaryScale{{ $post->id }}" tabindex="-1" aria-labelledby="postpleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="postpleModalgridLabel">{{__('pages.Update Post')}}</h5>

                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row g-3">
                                                                        <div class="col-md-12">
                                                                            <div>
                                                                                <label for="name_en" class="form-label">{{__('pages.Post Title')}}<span style="color:red;">*</span></label>

                                                                                <input type="text" class="form-control" name="name_en" id="name_en" value="{{ $post->name_en }}" required>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div>
                                                                                <label for="name_en" class="form-label">{{__('pages.Post Category')}}<span style="color:red;">*</span></label>

                                                                                <select name="post_category_id" id="post_category_id" class="form-control" required> 
                                                                                    <option value="">--Select Post Category--</option>
                                    
                                                                                    @foreach ($post_categorys as $post_category)
                                                                                        <option value="{{ $post_category->id }}" {{ $post->post_category_id == $post_category->id ? 'selected' : '' }}>{{ $post_category->name_en }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div>
                                                                                <label for="grade" class="form-label">{{__('pages.Grade')}}<span style="color:red;">*</span></label>

                                                                                <select name="grade" id="grade" class="form-control" required> 
                                                                                    <option value="">--Select Grade--</option>
                                    
                                                                                    @foreach ($grades as $grade)
                                                                                        <option value="{{ $grade->id }}" {{ $post->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name_en }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                                                <label class="form-check-label form-label" for="SwitchCheck11">{{__('pages.Status')}}</label>

                                                                                <input class="form-check-input form-control" type="checkbox" role="switch" name="status" id="SwitchCheck11" value="1" @if($post->status == 1) checked @endif>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
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
                                    {{ $posts->appends($_GET)->links() }}
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
        $(".delete").click(function(e) {
            var data_id = $(this).attr("data-id");
            var url =  '<a href="{{ route("admin.post.delete", ":id") }}" class="swal2-confirm swal2-styled" title="Delete">Confirm</a>';

            url = url.replace(':id', data_id );
            
            Swal.fire({
                title: 'Are you sure want to delete?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: url,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Deleted Successfully!', '', 'success')
                } else if (result.dismiss === "cancel") {
                    Swal.fire('Canceled', '', 'error')
                }
            })
        });
    </script>
@endpush