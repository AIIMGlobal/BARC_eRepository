@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Add Project Document')
@section('content')
    @push('css')
        <style>
            .kv-file-upload.btn.btn-sm.btn-kv.btn-default.btn-outline-secondary {
                display: none;
            }
            .bi-plus-lg.text-warning {
                display: none;
            }
            .file-drop-zone.clickable:hover {
                border: 1px dashed #aaa;
            }
        </style>
    @endpush

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Add Project Document</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('menu.Dashboard')}}</a></li>
                                <li class="breadcrumb-item active">Add Project Document</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Add Project Document</h4>
                            <div class="flex-shrink-0">
                                <a href="{{URL::previous()}}" class="btn btn-primary">{{__('pages.Back')}}</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.document.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type" value="1">

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="project_id" class="form-label">Project Name: <span style="color:red;">*</span></label>

                                            <select name="project_id" class="form-control select2" id="project_id" required>
                                                <option value="">--Select Project--</option>

                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8"></div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="user_ids" class="form-label">Assign Employee: </label>

                                        <select name="user_ids[]" multiple class="form-control select2" id="user_ids">
                                            @foreach ($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="hidden_area" style="max-width: 700px;">
                                            <table class="table table-bordered table-sm text-left table-area">
                                                <thead>
                                                    <tr>
                                                        <th>Documents<span style="color:red;">*</span></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="file" id="file-0" class="file-0" multiple name="file[]">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" checked value="1">
                                            <label class="form-check-label" for="statusOption">{{__('pages.Status')}}</label>
                                        </div>
                                    </div> --}}

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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

    <script>
        $('.add_more').click(function() {
            
            var clone = $(".table-area tbody tr:first-child").clone();
            $(".table-area tbody").append(clone);
            $(".table-area tbody tr:last-child td").find('input').val('');
        });

        function remove_tr(that) {
            if(confirm('Are you sure to remove ?')) {
                $(that).closest('tr').remove();
            }
        }
    </script>

    <script>
        $("#file-0").fileinput({
            theme: 'fa5',
            showUpload: false,
            showBrowse: false,
            uploadUrl: '#',
            browseOnZoneClick: true,
            initialPreviewShowDelete: true,
        });

        // const inputElement = document.querySelector("input[name='file']");
        // const pond = FilePond.create(inputElement);

        // $("#filepond").filepond({
        //     allowImagePreview: true,
        //     allowImageFilter: true,
        //     imagePreviewHeight: 200,
        //     allowMultiple: true,
        //     // allowFileTypeValidation: true,
        //     allowRevert: true,
        //     // acceptedFileTypes: ["image/png", "image/jpeg", "image/jpg"],
        //     maxFiles: 100,
        //     credits: false
        // });
    </script>
@endpush