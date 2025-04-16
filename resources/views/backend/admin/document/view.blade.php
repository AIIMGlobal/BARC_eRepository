@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View Document Detail')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View Document Detail</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                                <li class="breadcrumb-item active">View Document Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xxl-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">View Document Detail</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="name" class="form-label">Document Title</label>

                                        <input disabled id="name" type="text" class="form-control" name="name" value="{{ $document->name }}" placeholder="" required>
                                    </div>
                                </div>
                            </div>

                            @if ($document->type == 1)
                                <div class="row mt-4">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Project Name</label>

                                            <input disabled id="name" type="text" class="form-control" name="name" value="{{ $document->project->name ?? '' }}" placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                            @endif


                                @php
                                    $employees = explode(',', $document->user_ids);

                                    if ($employees) {
                                        $users = App\Models\User::whereIn('id', $employees)->get();
                                    } else {
                                        $users = [];
                                    }
                                @endphp

                                @if ($document->user_ids)
                                    <div class="row mt-4">
                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="name" class="form-label">Assigned Employees</label>

                                                <input disabled id="name" type="text" class="form-control" value="@if(count($users) > 0) @foreach($users as $user) {{ $user->name_en }} @if($loop->last) @else, @endif @endforeach @endif">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="file" class="form-label">Upload New Document: </label>

                                            <input id="file" type="file" class="form-control" name="file" {{ !($document->file) ? 'required' : '' }}>

                                            <br>

                                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewFile{{ $document->id }}">View</button>

                                            <div class="modal fade" id="viewFile{{ $document->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalgridLabel">View Document</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div>
                                                                        @if((pathinfo('storage/document/'.$document->file, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/document/'.$document->file, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/document/'.$document->file, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/document/'.$document->file, PATHINFO_EXTENSION) == 'gif'))
                                                                            <img src="{{ asset('storage/document') }}/{{ $document->file }}" style="width:100%;"/>
                                                                        @elseif (pathinfo('storage/document/'.$document->file, PATHINFO_EXTENSION) == 'pdf')
                                                                            <iframe src="{{ asset('storage/document') }}/{{ $document->file }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                        @else
                                                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/document') }}/{{ $document->file }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                        
                                                                <div class="col-lg-12">
                                                                    <div class="hstack gap-2 justify-content-end">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    </div>
                                                                </div><!--end col-->
                                                            </div><!--end row-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Created By</label>

                                            <input disabled id="name" type="text" class="form-control" name="name" value="{{ $document->createdBy->name_en ?? '' }}" placeholder="" required>
                                        </div>
                                    </div>
                                </div>

                                @if ($document->updated_by)
                                    <div class="row mt-4">
                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="name" class="form-label">Updated By</label>

                                                <input disabled id="name" type="text" class="form-control" name="name" value="{{ $document->updatedBy->name_en ?? '' }}" placeholder="" required>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div><!--end row-->
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
        // $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush