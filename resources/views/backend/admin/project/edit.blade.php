@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Edit project')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit project</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit project</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Edit project</h4>

                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Name: <span style="color:red;">*</span></label>

                                            <input id="name" type="text" class="form-control" name="name" value="{{ $project->name }}" placeholder="Enter Project Name">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="project_category" class="form-label">Project Category: <span style="color:red;">*</span></label>

                                        <select name="project_category" class="form-control select2" id="project_category" required>
                                            <option value="">--Select Project Category--</option>

                                            @foreach ($project_categories as $project_category)
                                                <option @if($project->category_id == $project_category->id) selected @endif value="{{ $project_category->id }}">{{ $project_category->name }}</option>
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
                                                <option @if($project->client_id == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div><!--end col-->
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="employees" class="form-label">Assign Employee: </label>

                                        <select name="employees[]" multiple class="form-control select2" id="employees">
                                            @foreach ($employees as $employee)
                                                <option @if(in_array($employee->id, explode(',',$project->employee_ids))) selected @endif value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="pm_id" class="form-label">Project Manager: <span style="color:red;">*</span></label>

                                        <select name="pm_id" class="form-control select2" id="pm_id" required>
                                            <option value="">--Select Project Manager--</option>

                                            @foreach ($employees as $employee)
                                                <option @if($project->pm_id == $employee->id) selected @endif value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="amount" class="form-label">Porject Budget: </label>

                                            <input id="amount" type="number" class="form-control" name="amount" value="{{ $project->amount }}" placeholder="Porject Budget" required>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="start_date" class="form-label">Porject Start Date: </label>

                                            <input id="start_date" value="{{ $project->start_date }}" type="date" class="form-control" name="start_date">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="end_date" class="form-label">Porject End Date: </label>

                                            <input id="end_date" value="{{ $project->end_date }}" type="date" class="form-control" name="end_date">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <label for="status" class="form-label">Status: </label>

                                        <select name="status" id="status" class="form-control select2">
                                            <option value="">--Select Status</option>

                                            <option value="0" {{ $project->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            <option value="1" {{ $project->status == 1 ? 'selected' : '' }}>On Going</option>
                                            <option value="2" {{ $project->status == 2 ? 'selected' : '' }}>Canceled</option>
                                            <option value="3" {{ $project->status == 3 ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>

                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Edit Project Value</h4>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="project_value" class="form-label">Project Value (Inc. Vat & Tax): <span style="color:red;">*</span></label>

                                            <input id="project_value" type="number" step="any" class="form-control" name="project_value" value="{{ $project_value->project_value ?? 0 }}" placeholder="Enter Project Value (Inc. Vat & Tax)" 
                                            @can('hod_permission')
                                                
                                            @else
                                                required
                                            @endcan>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="vat_tax" class="form-label">Vat & Tax Total Amount: <span style="color:red;">*</span></label>

                                            <input id="vat_tax" type="number" step="any" class="form-control" name="vat_tax" placeholder="Enter Vat & Tax Total Amount" value="{{ $project_value->vat_tax ?? 0 }}" 
                                            @can('hod_permission')
                                                
                                            @else
                                                required
                                            @endcan>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="remarks" class="form-label">Remarks (Optional): </label>

                                            <input id="remarks" type="text" class="form-control" name="remarks" placeholder="Enter Remarks (Optional)" value="{{ $project_value->remarks ?? '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12">
                                        <div>
                                            <label for="summery" class="form-label">Description & Details: </label>

                                            <textarea name="summery" class="form-control summernote">{!!($project->projectInfoData->summery ?? "") !!}</textarea>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                            <input autocomplete="off" class="form-check-input" type="checkbox" role="switch" name="status" id="upload_documents" value="1">
                                            <label class="form-check-label" for="upload_documents">Upload documents</label>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                    <label class="form-check-label form-label" for="SwitchCheck_5">Want to upload document</label>
                                                    <input class="form-check-input form-control" type="checkbox" role="switch" name="have_document" id="SwitchCheck_5" value="1" autocomplete="off">
                                                </div>
            
                                                <div class="hidden_area d-none" style="max-width: 600px;">
                                                    <table class="table table-bordered table-sm text-left table-area">
                                                        <thead>
                                                            <tr>
                                                            <th>{{__('pages.File Title')}} <span style="color:red;">*</span></th>
                                                            <th>{{__('pages.Documents')}} <span style="color:red;">*</span></th>
                                                            <th><span style="cursor: pointer;" class="btn btn-info btn-sm font-weight-bolder font-size-sm mr-3 add_more">+</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                            <tr>
                                                            <td>
                                                                <input type="text" name="file_title[]" class="form-control" placeholder="File Title">
                                                            </td>
                                                            <td>
                                                                <input type="file" class="form-control" name="file[]">
                                                            </td>
                                                            <td>
                                                                <span  onclick="remove_tr(this)" style="cursor: pointer;" class="btn btn-danger font-weight-bolder font-size-sm mr-3 remove_more">-</span>
                                                            </td>
                                                            </tr>
        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div> --}}

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
        $(document).ready(function() {
            $('.summernote').summernote();
        });

        $('#SwitchCheck_5').click(function() {
            var checked = this.checked;

            if (checked) {
                $('.hidden_area').removeClass('d-none');
                $('.table-area').find('input').attr('required','required');
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

        function remove_tr(that) {
            if(confirm('Are you sure to remove ?')) {
                $(that).closest('tr').remove();
            }
        }
    </script>
@endpush

@push('css')
    <style>
        .table-area tbody tr:first-child .remove_more {
            display: none;
        }
    </style>
@endpush