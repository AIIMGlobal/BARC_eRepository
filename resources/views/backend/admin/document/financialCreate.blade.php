@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Add Financial Document')
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
                        <h4 class="mb-sm-0">Add Financial Document</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Add Financial Document</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Add Financial Document</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.document.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type" value="4">

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="user_ids" class="form-label">Assign Employee: </label>

                                        <select name="user_ids[]" multiple class="form-control select2" id="user_ids">
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-8"></div>

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
                                                            <input type="file" id="file-0" name="file[]" multiple>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

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
        $('[href*="{{ $menu_expand }}"]').addClass('active');
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
    </script>
@endpush