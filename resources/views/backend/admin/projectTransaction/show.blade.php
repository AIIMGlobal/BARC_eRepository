@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View Project Transaction')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View Project Transaction</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">View Project Transaction</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View Project Transaction</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="project_id" class="form-label">Project Title: </label>

                                        <input type="text" class="form-control" id="project_id" value="{{ $projectTransaction->project->name ?? '-' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="type" class="form-label">Transaction Type: </label>

                                        <input type="text" class="form-control" id="type" value="{{ $projectTransaction->type == 1 ? 'Project Earning' : 'Project Expense' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="purpose" class="form-label">Purpose: </label>

                                        <input type="text" class="form-control" id="purpose" value="{{ $projectTransaction->purpose }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="amount" class="form-label">Amount: </label>

                                        <input type="text" class="form-control" id="amount" value="{{ number_format((float)$projectTransaction->amount ?? 0, 2, '.', '') }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 col-sm-6 col-xsm-12">
                                    <div>
                                        <label for="amount" class="form-label">Document: </label>

                                        <br>

                                        <a href="{{ asset('storage/transactionDocument') }}/{{ $projectTransaction->document }}" class="btn btn-info" download="">Download</a>

                                        <button type="button" class="btn btn-primary ml-4" data-bs-toggle="modal" data-bs-target="#viewFile{{ $projectTransaction->id }}">View</button>

                                        <div class="modal fade" id="viewFile{{ $projectTransaction->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalgridLabel">Document</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div>
                                                                    @if((pathinfo('storage/transactionDocument/'.$projectTransaction->document, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/transactionDocument/'.$projectTransaction->document, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/transactionDocument/'.$projectTransaction->document, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/transactionDocument/'.$projectTransaction->document, PATHINFO_EXTENSION) == 'gif'))
                                                                        <img src="{{ asset('storage/transactionDocument') }}/{{ $projectTransaction->document }}" style="width:100%;"/>
                                                                    @elseif (pathinfo('storage/transactionDocument/'.$projectTransaction->document, PATHINFO_EXTENSION) == 'pdf')
                                                                        <iframe src="{{ asset('storage/transactionDocument') }}/{{ $projectTransaction->document }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                    @else
                                                                        <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/transactionDocument') }}/{{ $projectTransaction->document }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush