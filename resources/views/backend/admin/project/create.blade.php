@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Add Project')
@section('content')
    @push('css')
        <style>
            .table-area tbody tr:first-child .remove_more {
                display: none;
            }
            /**
            * FilePond Custom Styles
            */
            .filepond--drop-label {
                color: #4c4e53;
            }

            .filepond--label-action {
                text-decoration-color: #babdc0;
            }

            .filepond--panel-root {
                border-radius: 2em;
                background-color: #edf0f4;
                height: 1em;
            }

            .filepond--item-panel {
                background-color: #595e68;
            }

            .filepond--drip-blob {
                background-color: #7f8a9a;
            }

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
                        <h4 class="mb-sm-0">Add Project</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Add Project</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Add Project</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('admin.project.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Name: <span style="color:red;">*</span></label>

                                            <input id="name" type="text" class="form-control" name="name" placeholder="Enter Project Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="project_category" class="form-label">Project Category: <span style="color:red;">*</span></label>

                                        <select name="project_category" class="form-control select2" id="project_category" required>
                                            <option value="">--Select Project Category--</option>

                                            @foreach ($project_categories as $project_category)
                                                <option value="{{ $project_category->id }}">{{ $project_category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div><!--end col-->
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="client_id" class="form-label">Client: <span style="color:red;">*</span></label>

                                        <select name="client_id" class="form-control select2" id="client_id" 
                                        @can('hod_permission')
                                            
                                        @else
                                            required
                                        @endcan>
                                            <option value="">--Select Client--</option>

                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div><!--end col-->
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="employees" class="form-label">Assign Employee: </label>

                                        <select name="employees[]" multiple class="form-control select2" id="employees">

                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="pm_id" class="form-label">Project Manager: <span style="color:red;">*</span></label>

                                        <select name="pm_id" class="form-control select2" id="pm_id" required>
                                            <option value="">--Select Project Manager--</option>

                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div> 

                                    {{-- <div class="col-md-4 col-sm-12">
                                        <label for="amount" class="form-label">Porject Budget: <span style="color:red;">*</span></label>

                                        <input id="amount" type="number" class="form-control" name="amount" placeholder="Porject Budget" required>
                                    </div> --}}

                                    <div class="col-md-4 col-sm-12">
                                        <label for="start_date" class="form-label">Porject Start Date: </label>

                                        <input id="start_date" type="date" class="form-control" name="start_date">
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="end_date" class="form-label">Porject End Date: </label>

                                        <input id="end_date" type="date" class="form-control" name="end_date">
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" checked value="1">

                                            <label class="form-check-label" for="statusOption">Status</label>
                                        </div>
                                    </div><!--end col-->
                                    
                                    <div class="col-md-12 col-sm-12">
                                        <label for="summery" class="form-label">Description & Details: </label>

                                        <textarea name="summery" class="form-control summernote"></textarea>
                                    </div>

                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Add Project Value</h4>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="project_value" class="form-label">Project Value (Inc. Vat & Tax): <span style="color:red;">*</span></label>

                                            <input id="project_value" type="number" step="any" class="form-control" name="project_value" placeholder="Enter Project Value (Inc. Vat & Tax)" 
                                            @can('hod_permission')
                                                
                                            @else
                                                required
                                            @endcan>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="vat_tax" class="form-label">Vat & Tax Total Amount: <span style="color:red;">*</span></label>

                                            <input id="vat_tax" type="number" step="any" class="form-control" name="vat_tax" placeholder="Enter Vat & Tax Total Amount" 
                                            @can('hod_permission')
                                                
                                            @else
                                                required
                                            @endcan>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="remarks" class="form-label">Remarks (Optional): </label>

                                            <input id="remarks" type="text" class="form-control" name="remarks" placeholder="Enter Remarks (Optional)">
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                            <input autocomplete="off" class="form-check-input" type="checkbox" role="switch" name="status" id="upload_documents" value="1">

                                            <label class="form-check-label" for="upload_documents">Upload Documents: </label>
                                        </div>
                                    </div> --}}

                                    <div class="col-lg-12">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                    <label class="form-check-label form-label" for="SwitchCheck_5">Want to upload documents?</label>

                                                    <input class="form-check-input form-control" type="checkbox" role="switch" name="have_document" id="SwitchCheck_5" value="1" autocomplete="off">
                                                </div>
            
                                                <div class="hidden_area d-none">
                                                    <table class="table table-bordered table-sm text-left table-area">
                                                        <thead>
                                                            <tr>
                                                                {{-- <th>File Title: <span style="color:red;">*</span></th> --}}
                                                                <th>Documents: <span style="color:red;">*</span></th>
                                                                
                                                                {{-- <th><span style="cursor: pointer;" class="btn btn-info btn-sm font-weight-bolder font-size-sm mr-3 add_more">+</span></th> --}}
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr>
                                                                {{-- <td>
                                                                    <input type="text" name="file_title[]" class="form-control" placeholder="Enter File Title">
                                                                </td> --}}

                                                                <td>
                                                                    <input type="file" id="file-0" name="file[]" multiple>
                                                                </td>

                                                                {{-- <td>
                                                                    <span  onclick="remove_tr(this)" style="cursor: pointer;" class="btn btn-danger font-weight-bolder font-size-sm mr-3 remove_more">-</span>
                                                                </td> --}}
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="projectTransaction_div">
                                        <div class="col-md-12">
                                            <div class="projectTransaction_btn">
                                                <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="has_projectTransaction" id="has_projectTransaction" value="1">
                                                    <label class="form-check-label" for="has_projectTransaction">Add Project Transaction Detail</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <div class="projectTransaction_inner" style="display: none;">
                                                <table class="table table-responsive table-bordered mb-0" id="job_tbl_posts" style="text-align: center;">
                                                    <thead>
                                                        <tr>
                                                            <th>Transaction Type <span style="color:red;">*</span></th>
                                                            <th>Purpose <span style="color:red;">*</span></th>
                                                            <th>Amount <span style="color:red;">*</span></th>
                                                            <th>Document</th>
                                                            <th>More</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody id="job_tbl_posts_body">
                                                        <tr id="job_rec_1">
                                                            <td>
                                                                <select name="type[]" id="type1" class="form-control type1 error_class">
                                                                    <option value="">--Select Transaction Type--</option>
        
                                                                    <option value="1">Project Earning</option>
                                                                    <option value="2">Project Expense</option>
                                                                </select>
                                                            </td>
        
                                                            <td><input name="purpose[]" class="form-control purpose1 error_class" id="purpose1" placeholder="Enter Purpose"/></td>
        
                                                            <td><input type="number" min="1" name="transactionAmount[]" placeholder="Enter Amount" class="amount1 form-control error_class" id="amount1"></td>
        
                                                            <td><input type="file" name="document[]" class="document1 form-control" id="document1"></td>
        
                                                            <td>
                                                                <button class="btn btn-sm btn-primary job_add_record" type="button" style="padding: 0.5; font-size: 16px;"><i class="las la-plus-circle"></i> </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>

                            <div style="display:none;">
                                <table id="job_table" style="text-align: center;">
                                    <tr id="">
                                        <td>
                                            <select name="type[]" id="type" class="form-control type1 type">
                                                <option value="">--Select Transaction Type--</option>
    
                                                <option value="1">Project Earning</option>
                                                <option value="2">Project Expense</option>
                                            </select>
                                        </td>
    
                                        <td><input name="purpose[]" class="form-control purpose1 purpose" id="purpose" placeholder="Enter Purpose"/></td>
    
                                        <td><input type="number" min="1" name="transactionAmount[]" placeholder="Enter Amount" class="amount1 form-control amount" id="amount"></td>
    
                                        <td><input type="file" name="document[]" class="document1 document form-control" id="document"></td>
            
                                        <td> 
                                            <div class="input-group-btn text-left"> 
                                                <button class="btn btn-sm btn-danger job_delete_record" onclick="remove_tr(this)" type="button" data-id="0" style="padding: 0.5rem; font-size: 16px"><i class="las la-minus-circle"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
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

    <script>
        $('#SwitchCheck_5').click(function() {
            var checked = this.checked;

            if (checked) {
                $('.hidden_area').removeClass('d-none');
                // $('.table-area').find('input').attr('required','required');
            } else {
                $('.hidden_area').addClass('d-none');
                $('.table-area').find('input').removeAttr('required');
            }
        });

        $('.add_more').click(function() { 
            var clone = $(".table-area tbody tr:first-child").clone();

            $(".table-area tbody").append(clone);
            $(".table-area tbody tr:last-child td").find('input').val('');
        });
    </script>

    <script>
        $('#has_projectTransaction').on('click', function() {
            if ($('#has_projectTransaction').is(':checked')) {
                $('.projectTransaction_inner').show();
            } else {
                $('.projectTransaction_inner').hide();

                let error_arr = [];

                $('span.text-danger').hide();
            }
        });
    </script>

    <script>
        $('#submitBtn').on('click', function () {
            if ($('#has_projectTransaction').is(':checked')) {
                let error_arr = [];

                $('span.text-danger').hide();

                $.each($('.error_class'),function() {
                    if (!$(this).val()) {
                        error_arr.push({
                            "text": "<span class='text-danger'>Input required</span>",
                            "div": "#"+$(this).attr('id')
                        });
                    }
                });

                if (error_arr.length > 0) {
                    $.each(error_arr, function(key, value) {
                        $(value.text).insertAfter(value.div);
                    });

                    Swal.fire({
                        title: 'Please fill out the fields!',
                        icon: 'error',
                        showCancelButton: false,
                    });
                    
                    return false;
                }
            }
        });
    </script>

    <script> 
        $(document).delegate('button.job_add_record', 'click', function(e) {
            e.preventDefault(); 

            let content = $('#job_table tr');
            size = $('#job_tbl_posts >tbody >tr').length + 1;
            
            element = null;
            element = content.clone();
            element.attr('id', 'job_rec_'+size);

            element.find('.type').attr('id', 'type'+size);
            element.find('.purpose').attr('id', 'purpose'+size);
            element.find('.amount').attr('id', 'amount'+size);
            element.find('.document').attr('id', 'document'+size);

            element.find('#type'+size).addClass('error_class');
            element.find('#purpose'+size).addClass('error_class');
            element.find('#amount'+size).addClass('error_class');
            // element.find('#document'+size).addClass('error_class');

            element.find('.job_delete_record').attr('data-id', size);
            
            element.appendTo('#job_tbl_posts_body');
        });

        // $(document).delegate('button.job_delete_record', 'click', function(e) {
        //     e.preventDefault();    

        //     let id = $(this).attr('data-id');
            
        //     $('#job_rec_'+ id).remove();

        //     return true;
        // });

        function remove_tr(that) {
            if (confirm('Are you sure want to remove?')) {
                $(that).closest('tr').remove();
            }
        }
    </script>

    {{-- <script>
        // Get a reference to the file input element
        const inputElement = document.querySelector('.filepond');

        // Create the FilePond instance
        const pond = FilePond.create(inputElement, {
            allowMultiple: true,
            allowReorder: true,
        });

        // Easy console access for testing purposes
        window.pond = pond;
    </script> --}}

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