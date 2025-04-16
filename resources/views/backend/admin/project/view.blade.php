@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | View Project Details')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">View Project Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">View Project Details</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View Project Details</h4>

                            <div class="flex-shrink-0">
                                @can('view_pl_report')
                                    <a href="{{ route('admin.report.showPL', Crypt::encryptString($project->id)) }}" title="View P AND L Report" type="button" class="btn btn-success waves-effect waves-light">
                                        View P&L Report
                                    </a>
                                @endcan
                                
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="name" class="form-label">Name: </label>

                                            <input disabled id="name" type="text" class="form-control" name="name" value="{{ $project->name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="category_id" class="form-label">Project Category: </label>

                                            <input disabled id="category_id" type="text" class="form-control" name="category_id" value="{{ $project->category->name ?? '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="client_id" class="form-label">Client: </label>

                                            <input disabled id="client_id" type="text" class="form-control" name="client_id" value="{{ $project->client->name_en ?? '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <label for="employees" class="form-label">Assign Employees: </label>

                                        <select disabled name="employees[]" multiple class="form-control select2" id="employees">
                                            @foreach ($employees as $employee)
                                                <option @if(in_array($employee->id, explode(',',$project->employee_ids))) selected @endif value="{{ $employee->id }}">{{ $employee->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="pm_id" class="form-label">Project Manager: </label>

                                            <input disabled id="pm_id" type="text" class="form-control" name="pm_id" value="{{ $project->pm->name_en ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="amount" class="form-label">Porject Budget</label>
                                            <input disabled id="amount" type="number" class="form-control" name="amount" value="{{$project->amount}}" placeholder="Porject Budget" required>
                                            
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="start_date" class="form-label">Porject Start Date: </label>

                                            <input disabled id="start_date" value="{{ $project->start_date }}" type="date" class="form-control" name="start_date">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="end_date" class="form-label">Porject End Date: </label>

                                            <input disabled id="end_date" value="{{ $project->end_date }}" type="date" class="form-control" name="end_date">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-4">
                                        <div class="form-check form-switch form-switch-custom form-switch-success mt-4">
                                            <input disabled class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" @if($project->status == 1) checked @endif value="1">
                                            <label class="form-check-label" for="statusOption">Status</label>
                                        </div>
                                    </div><!--end col-->

                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Project Value Details</h4>
                                    </div>

                                    @if ($project_value)
                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="project_value" class="form-label">Project Value (Inc. Vat & Tax): </label>

                                                <input id="project_value" type="text" step="any" class="form-control" name="project_value" value="{{ number_format((float)$project_value->project_value ?? 0, 2, '.', '') }}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="vat_tax" class="form-label">Vat & Tax Total Amount: </label>
    
                                                <input id="vat_tax" type="text" step="any" class="form-control" name="vat_tax" value="{{ number_format((float)$project_value->vat_tax ?? 0, 2, '.', '') }}" disabled>
                                            </div>
                                        </div>
    
                                        @if ($project_value->remarks)
                                            <div class="col-md-4 col-sm-12">
                                                <div>
                                                    <label for="remarks" class="form-label">Remarks: </label>
    
                                                    <input id="remarks" type="text" class="form-control" name="remarks" value="{{ $project_value->remarks }}" disabled>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="project_value" class="form-label">Project Value (Inc. Vat & Tax): </label>

                                                <input id="project_value" type="text" step="any" class="form-control" name="project_value" value="0.00" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="vat_tax" class="form-label">Vat & Tax Total Amount: </label>
    
                                                <input id="vat_tax" type="text" step="any" class="form-control" name="vat_tax" value="0.00" disabled>
                                            </div>
                                        </div>
    
                                        <div class="col-md-4 col-sm-12">
                                            <div>
                                                <label for="remarks" class="form-label">Remarks: </label>

                                                <input id="remarks" type="text" class="form-control" name="remarks" value="" disabled>
                                            </div>
                                        </div>
                                    @endif

                                    @php
                                        $totalEarning = 0;
                                        $totalExpense = 0;
                                        $netProfit = 0;
                                        // $netProfit += $project_value->project_value;
                                        // $netProfit = $netProfit - $project_value->vat_tax;
                                        
                                        if (count($projectEarnings) > 0) {
                                            foreach ($projectEarnings as $projectEarning) {
                                                $totalEarning += $projectEarning->amount;
                                                $netProfit += $projectEarning->amount ?? 0;
                                            }
                                        }

                                        if (count($projectExpenses) > 0) {
                                            foreach ($projectExpenses as $projectExpense) {
                                                $totalExpense += $projectExpense->amount;
                                                $netProfit = $netProfit - ($projectExpense->amount ?? 0);
                                            }
                                        }
                                    @endphp

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Project Value v/s Project Transaction</h4>
                                            </div>

                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        @if ($project_value)
                                                            <tr>
                                                                <th>Project Value</th>
                                                                <td>{{ number_format((float)$project_value->project_value ?? 0, 2, '.', '') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Vat and Tax</th>
                                                                <td>{{ number_format((float)$project_value->vat_tax ?? 0, 2, '.', '') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Project Earning</th>
                                                                <td>{{ number_format((float)$totalEarning ?? 0, 2, '.', '') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Project Expense</th>
                                                                <td>{{ number_format((float)$totalExpense ?? 0, 2, '.', '') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Net Profit</th>
                                                                <td>{{ number_format((float)$netProfit ?? 0, 2, '.', '') }}</td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <th>Project Value</th>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Vat and Tax</th>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Project Earning</th>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Project Expense</th>
                                                                <td>0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Net Profit</th>
                                                                <td>0.00</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12">
                                        <div>
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Description & Details</h4>
                                            </div>

                                            <br>

                                            {!! ($project->projectInfoData->summery ?? "") !!}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="summery" class="form-label">Documents: </label>

                                            <div class="hidden_area">
                                                <table class="table table-bordered table-sm text-left table-area">
                                                    <thead>
                                                        <tr>
                                                            <th>File Title</th>
                                                            <th>Documents</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($project->projectDocumentData as $projectDocument)
                                                            <tr>
                                                                <td>
                                                                    {{ $projectDocument->name }}
                                                                </td>

                                                                <td>
                                                                    <a href="{{ asset('storage/document') }}/{{ $projectDocument->file }}" download="" target="#" rel="noopener noreferrer">Download</a>

                                                                    <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewDoc{{ $projectDocument->id }}">View</button>

                                                                    <div class="modal fade" id="viewDoc{{ $projectDocument->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
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
                                                                                                @if((pathinfo('storage/document/'.$projectDocument->file, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/document/'.$projectDocument->file, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/document/'.$projectDocument->file, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/document/'.$projectDocument->file, PATHINFO_EXTENSION) == 'gif'))
                                                                                                    <img src="{{ asset('storage/document') }}/{{ $projectDocument->file }}" style="max-width: 400px;"/>
                                                                                                @elseif (pathinfo('storage/document/'.$projectDocument->file, PATHINFO_EXTENSION) == 'pdf')
                                                                                                    <iframe src="{{ asset('storage/document') }}/{{ $projectDocument->file }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                                                @else
                                                                                                    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/document') }}/{{ $projectDocument->file }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
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
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    @can('access_project_transaction')
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Project Transaction Details</h4>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Project Earnings</h4>
                                                </div>

                                                <div class="card-body">
                                                    @if (count($projectEarnings) > 0)
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Purpose</th>
                                                                    <th style="text-align: center;">Amount</th>
                                                                    <th style="text-align: center;">Document</th>
                                                                </tr>
                                                            </thead>
            
                                                            <tbody>
                                                                @foreach ($projectEarnings as $projectEarning)
                                                                    <tr>
                                                                        <td>{{ $projectEarning->purpose }}</td>
                                                                        <td style="text-align: center;">{{ number_format((float)$projectEarning->amount ?? 0, 2, '.', '') }}</td>
                                                                        <td style="text-align: center;">
                                                                            @if ($projectEarning->document)
                                                                                <a href="{{ asset('storage/transactionDocument/' . $projectEarning->document) }}" class="btn btn-primary" download="">Download</a>
                                                                            @else
                                                                                N/A
                                                                            @endif

                                                                            <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewFile{{ $projectEarning->id }}">View</button>

                                                                            <div class="modal fade" id="viewFile{{ $projectEarning->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
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
                                                                                                        @if((pathinfo('storage/transactionDocument/'.$projectEarning->document, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/transactionDocument/'.$projectEarning->document, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/transactionDocument/'.$projectEarning->document, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/transactionDocument/'.$projectEarning->document, PATHINFO_EXTENSION) == 'gif'))
                                                                                                            <img src="{{ asset('storage/transactionDocument') }}/{{ $projectEarning->document }}" style="max-width: 400px;"/>
                                                                                                        @elseif (pathinfo('storage/transactionDocument/'.$projectEarning->document, PATHINFO_EXTENSION) == 'pdf')
                                                                                                            <iframe src="{{ asset('storage/transactionDocument') }}/{{ $projectEarning->document }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                                                        @else
                                                                                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/transactionDocument') }}/{{ $projectEarning->document }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
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
                                                                    </tr>

                                                                    @php
                                                                        $totalEarning += $projectEarning->amount;
                                                                    @endphp
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <tr>
                                                            <td>No Record Found</td>
                                                        </tr>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Project Expenses</h4>
                                                </div>

                                                <div class="card-body">
                                                    @if (count($projectExpenses) > 0)
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Purpose</th>
                                                                    <th style="text-align: center;">Amount</th>
                                                                    <th style="text-align: center;">Document</th>
                                                                </tr>
                                                            </thead>
            
                                                            <tbody>
                                                                @foreach ($projectExpenses as $projectExpense)
                                                                    <tr>
                                                                        <td>{{ $projectExpense->purpose }}</td>
                                                                        <td style="text-align: center;">{{ number_format((float)$projectExpense->amount ?? 0, 2, '.', '') }}</td>
                                                                        <td style="text-align: center;">
                                                                            @if ($projectExpense->document)
                                                                                <a href="{{ asset('storage/transactionDocument/' . $projectExpense->document) }}" class="btn btn-primary" download="">Download</a>
                                                                            @else
                                                                                N/A
                                                                            @endif

                                                                            <button type="button" class="btn btn-info ml-4" data-bs-toggle="modal" data-bs-target="#viewFile{{ $projectExpense->id }}">View</button>

                                                                            <div class="modal fade" id="viewFile{{ $projectExpense->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
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
                                                                                                        @if((pathinfo('storage/transactionDocument/'.$projectExpense->document, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/transactionDocument/'.$projectExpense->document, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/transactionDocument/'.$projectExpense->document, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/transactionDocument/'.$projectExpense->document, PATHINFO_EXTENSION) == 'gif'))
                                                                                                            <img src="{{ asset('storage/transactionDocument') }}/{{ $projectExpense->document }}" style="max-width: 400px;"/>
                                                                                                        @elseif (pathinfo('storage/transactionDocument/'.$projectExpense->document, PATHINFO_EXTENSION) == 'pdf')
                                                                                                            <iframe src="{{ asset('storage/transactionDocument') }}/{{ $projectExpense->document }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                                                        @else
                                                                                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/transactionDocument') }}/{{ $projectExpense->document }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
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
                                                                    </tr>

                                                                    @php
                                                                        $totalExpense += $projectExpense->amount;
                                                                    @endphp 
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <tr>
                                                            <td>No Record Found</td>
                                                        </tr>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
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