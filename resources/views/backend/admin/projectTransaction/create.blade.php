@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Add Project Transaction')
@section('content')

@push('css')
    <style>
        .table-area tbody tr:first-child .remove_more {
            display: none;
        }
    </style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Project Transaction</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Project Transaction</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Add Project Transaction</h4>

                        <div class="flex-shrink-0">
                            <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.project_transaction.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-4 col-sm-12">
                                    <label for="project_id" class="form-label">Project: <span style="color:red;">*</span></label>

                                    <select name="project_id" class="form-control select2" id="project_id" required>
                                        <option value="">--Select Project--</option>

                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div><!--end col-->
                                
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <table class="table table-responsive table-bordered mb-0" id="job_tbl_posts" style="text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th>Transaction Type</th>
                                                    <th>Purpose</th>
                                                    <th>Amount</th>
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

                                                    <td><input type="number" min="1" name="amount[]" placeholder="Enter Amount" class="amount1 form-control error_class" id="amount1"></td>

                                                    <td><input type="file" name="document[]" class="document1 form-control" id="document1"></td>

                                                    <td>
                                                        <button class="btn btn-sm btn-primary job_add_record" type="button" style="padding: 0.5; font-size: 16px;"><i class="las la-plus-circle"></i> </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                </div>
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

                                    <td><input type="number" min="1" name="amount[]" placeholder="Enter Amount" class="amount1 form-control amount" id="amount"></td>

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
            // element.find('.document').attr('id', 'document'+size);

            element.find('#type'+size).addClass('error_class');
            element.find('#purpose'+size).addClass('error_class');
            element.find('#amount'+size).addClass('error_class');

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
            if(confirm('Are you sure to remove?')) {
                $(that).closest('tr').remove();
            }
        }
    </script>

    <script>
        $('#submitBtn').on('click', function() {
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
        });
    </script>
@endpush