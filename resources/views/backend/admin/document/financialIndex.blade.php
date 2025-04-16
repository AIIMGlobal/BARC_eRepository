@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Financial Document List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Financial Document List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Financial Document List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Financial Document List</h4>

                            <div class="flex-shrink-0">
                                @can('add_financial_document')
                                    <a class="btn btn-primary" href="{{ route('admin.document.financialCreate') }}">
                                        Add New Financial Document
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- end card header -->

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['name']) && $_GET['name']!='') value="{{ $_GET['name'] }}" @endif type="text" class="form-control search" name="name" placeholder="Search by Document Name">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-4">
                                        <div>
                                            <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> 
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Filter
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div>
                                            <a style="max-width: 150px;" href="{{ route('admin.document.financialIndex') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </form>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-success mb-2" id="selectAll">Select All</button>

                                    <button class="btn btn-sm btn-danger mb-2" id="unselectAll">Unselect All</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Name</th>
                                            <th>Download</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($documents->count() > 0)
                                            @php
                                                $i = ($documents->perPage() * ($documents->currentPage() - 1) +1);
                                            @endphp

                                            <form action="{{ route('admin.document.massDelete') }}" method="post">
                                                @csrf

                                                @can('delete_document')
                                                    <button type="submit" class="btn btn-danger mb-3" id="massDelete" style="display: none;" onclick="return confirm('Are you sure, you want to delete ?')">Delete</button>
                                                @endcan

                                                @foreach ($documents as $document)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input class="document" type="checkbox" name="document[]" id="document{{ $i }}" value="{{ $document->id }}">

                                                            {{ $i }}
                                                        </td>

                                                        <td>{{ $document->name ?? '-' }}</td>
                                                        
                                                        <td>
                                                            <a href="{{ asset('storage/document') }}/{{ $document->file }}" class="btn btn-primary" download="">Download</a>  

                                                            <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewFile{{ $document->id }}">View</button>

                                                            <div class="modal fade" id="viewFile{{ $document->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
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
                                                        </td>

                                                        <td class="text-center">
                                                            @can('view_document')
                                                                <a href="{{ route('admin.document.show', Crypt::encryptString($document->id)) }}" title="View " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                    <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endcan

                                                            @can('edit_document')
                                                                <a href="{{ route('admin.document.edit', Crypt::encryptString($document->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                    <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endcan

                                                            @can('delete_document')
                                                                <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.document.delete', Crypt::encryptString($document->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                    <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </form>
                                        @else
                                            <tr>
                                                <td colspan="100%" class="text-center"><b>No Data Found</b></td>
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
                            {{ $documents->links() }}
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
@endsection

@push('script')
    <script>
        $("#selectAll").click(function() {
            $(".document").prop('checked', true);

            $('#massDelete').show();
        });

        $("#unselectAll").click(function() {
            $(".document").prop('checked', false);

            $('#massDelete').hide();
        });

        $('.document').on('click', function() {
            if ($('.document:checkbox:checked').length > 0) {
                $('#massDelete').show();
            } else {
                $('#massDelete').hide();
            }
        });
    </script>
@endpush