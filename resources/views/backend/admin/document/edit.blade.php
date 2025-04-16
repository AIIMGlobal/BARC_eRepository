@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Edit Document')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit Document</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Document</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit Document</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        

                        <div class="card-body">
                            <form action="{{ route('admin.document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type" value="{{ $document->type }}">

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="user_ids" class="form-label">Assigned Employees: </label>

                                        <select name="user_ids[]" multiple class="form-control select2" id="user_ids">
                                            @foreach ($employees as $employee)
                                                <option @if(in_array($employee->id, explode(',', $document->user_ids))) selected @endif value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8"></div>

                                @if ($document->type == 1)
                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div>
                                            <label for="project_id" class="form-label">Project Name: <span style="color:red;">*</span></label>

                                            <select name="project_id" class="form-control select2" id="project_id" required>
                                                @foreach ($projects as $project)
                                                    <option {{ $project->id == $document->project_id ? 'selected' : '' }} value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8"></div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Document Name: <span style="color:red;">*</span></label>

                                            <input id="name" type="text" class="form-control" name="name" value="{{ $document->name }}" placeholder="Document Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-8"></div>

                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div>
                                            <label for="file" class="form-label">Document: </label>

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

                                    <div class="col-md-8"></div>

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>
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
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush