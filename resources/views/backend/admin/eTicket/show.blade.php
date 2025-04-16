@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | E-Ticket Details')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">E-Ticket Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">E-Ticket Details</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="offset-md-1 col-md-10">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex bg-info text-center">
                            <h4 class="card-title mb-0 flex-grow-1 text-white">E-Ticket Details</h4>
                        </div>

                        <div class="card-body">
                            <div class="box">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="client_id" class="form-label">Authority</label>

                                            <input type="text" value="{{ $eTicket->client->name_en ?? '' }}" class="form-control" id="client_id" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div>
                                            <label for="project_id" class="form-label">Project</label>

                                            <input type="text" value="{{ $eTicket->project->name ?? '' }}" class="form-control" id="project_id" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="title" class="form-label">Issue</label>

                                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Your Issue" value="{{ $eTicket->title }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="ticket_type_id" class="form-label">E-Ticket Type</label>

                                            <input type="text" class="form-control" id="ticket_type_id" name="ticket_type_id" placeholder="Enter Your Issue" value="{{ $eTicket->type->title ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12 mt-4">
                                        <div>
                                            <label for="description" class="form-label">Describe Your Issue</label>

                                            <textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="10" readonly>{{ $eTicket->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="priority" class="form-label">Priority</label>

                                            <input type="text" class="form-control" id="priority" name="priority" value="{{ $eTicket->priority ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 mt-4">
                                        <div>
                                            <label for="status" class="form-label">E-Ticket Status</label>
                                            
                                            @foreach (ticketStatuses() as $key => $status)
                                                @if ($key == $eTicket->status)
                                                    <input type="text" class="form-control" name="status" value="{{ $status }}" readonly>
                                                @endif
                                            @endforeach
                                        </div>

                                        @can('change_eTicket_status')
                                            <div class="mt-2 hstack gap-2 justify-content-end">
                                                @if ($eTicket->status == 0)
                                                    <a onclick="return confirm('Are you sure, you want to accept the ticket?')" href="{{ route('admin.eTicket.changeStatus', Crypt::encryptString($eTicket->id)) }}?value=1" title="Accept" class="btn btn-success">
                                                        Accept
                                                    </a>

                                                    <button type="button" class="btn btn-danger ml-4" data-bs-toggle="modal" data-bs-target="#decline{{ $eTicket->id }}">Decline</button>
                                                @elseif ($eTicket->status == 1)
                                                    <button type="button" class="btn btn-success ml-4" data-bs-toggle="modal" data-bs-target="#solve{{ $eTicket->id }}">Solve</button>

                                                    <button type="button" class="btn btn-danger ml-4" data-bs-toggle="modal" data-bs-target="#decline{{ $eTicket->id }}">Decline</button>
                                                @endif

                                                <div class="modal fade" id="solve{{ $eTicket->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">E-Ticket Solved</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.eTicket.changeStatus', Crypt::encryptString($eTicket->id)) }}" method="get">
                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            @csrf
                                                                            
                                                                            <input type="hidden" value="2" name="value">

                                                                            <textarea name="remarks" id="remarks" rows="5" class="form-control" placeholder="Enter Remarks">{{ $eTicket->remarks }}</textarea>
                                                                        </div>
                                                                            
                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="submit" class="btn btn-success">Solved</button>

                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                            </div>
                                                                        </div><!--end col-->
                                                                    </div><!--end row-->
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="decline{{ $eTicket->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalgridLabel">Decline E-Ticket</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.eTicket.changeStatus', Crypt::encryptString($eTicket->id)) }}" method="get">
                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            @csrf
                                                                            
                                                                            <input type="hidden" value="3" name="value">

                                                                            <textarea name="remarks" id="remarks" rows="5" class="form-control" placeholder="Enter Remarks">{{ $eTicket->remarks }}</textarea>
                                                                        </div>
                                                                        
                                                                        <div class="col-lg-12">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="submit" class="btn btn-danger">Decline</button>

                                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                            </div>
                                                                        </div><!--end col-->
                                                                    </div><!--end row-->
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
            
                                    @if (count($eTicketFiles) > 0)
                                        <div class="col-md-6 col-sm-12 mt-4 text-center">

                                            <div class="">
                                                <table class="table table-bordered table-sm text-left table-area">
                                                    <thead>
                                                        <tr>
                                                            <th>Attachments</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($eTicketFiles as $eTicketFile)
                                                            <tr>
                                                                <td>
                                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewDoc{{ $eTicketFile->id }}">View</button>

                                                                    <div class="modal fade" id="viewDoc{{ $eTicketFile->id }}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
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
                                                                                                @if((pathinfo('storage/eTicket/'.$eTicketFile->file, PATHINFO_EXTENSION) == 'png') || (pathinfo('storage/eTicket/'.$eTicketFile->file, PATHINFO_EXTENSION) == 'jpg') || (pathinfo('storage/eTicket/'.$eTicketFile->file, PATHINFO_EXTENSION) == 'jpeg') || (pathinfo('storage/eTicket/'.$eTicketFile->file, PATHINFO_EXTENSION) == 'gif'))
                                                                                                    <img src="{{ asset('storage/eTicket') }}/{{ $eTicketFile->file }}" style="max-width: 400px;"/>
                                                                                                @elseif (pathinfo('storage/eTicket/'.$eTicketFile->file, PATHINFO_EXTENSION) == 'pdf')
                                                                                                    <iframe src="{{ asset('storage/eTicket') }}/{{ $eTicketFile->file }}" frameborder="0" style="width:100%; min-height:640px;"></iframe>
                                                                                                @else
                                                                                                    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('storage/eTicket') }}/{{ $eTicketFile->file }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
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

                                                                <td>
                                                                    <a href="{{ asset('storage/eTicket') }}/{{ $eTicketFile->file }}" class="btn btn-success" download="" target="#" rel="noopener noreferrer">Download</a>

                                                                    <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.eTicket.deleteFile', Crypt::encryptString($eTicketFile->id)) }}" title="Delete" class="btn btn-danger ml-4">
                                                                        Delete
                                                                    </a>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    @can('eTicket_status_record')
                                        <div class="card-header">
                                            <h4>Ticket Status Record</h4>
                                        </div>

                                        <div class="col-md-12 col-sm-12 mt-4 text-center">
                                            <div class="">
                                                <table class="table table-bordered table-sm table-area">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th style="text-align: left;">Changed By</th>
                                                            <th>Changed At</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>Pending</td>
                                                            <td style="text-align: left;">{{ $eTicket->createdBy->name_en ?? '' }}</td>
                                                            <td>{{ date('d M, Y', strtotime($eTicket->created_at)) }} at {{ date('h:i a', strtotime($eTicket->created_at)) }}</td>
                                                        </tr>

                                                        @if ($eTicket->acceptedBy)
                                                            <tr>
                                                                <td>Accept</td>
                                                                <td style="text-align: left;">{{ $eTicket->acceptedBy->name_en ?? '' }}</td>
                                                                <td>{{ date('d M, Y', strtotime($eTicket->accepted_at)) }} at {{ date('h:i a', strtotime($eTicket->accepted_at)) }}</td>
                                                            </tr>
                                                        @endif

                                                        @if ($eTicket->solvedBy)
                                                            <tr>
                                                                <td>Solve</td>
                                                                <td style="text-align: left;">
                                                                    {{ $eTicket->solvedBy->name_en ?? '' }}
                                                                
                                                                    <br>

                                                                    @if ($eTicket->remarks)
                                                                        **Remarks: {{ $eTicket->remarks }}
                                                                    @endif
                                                                </td>

                                                                <td>{{ date('d M, Y', strtotime($eTicket->solved_at)) }} at {{ date('h:i a', strtotime($eTicket->solved_at)) }}</td>
                                                            </tr>
                                                        @endif

                                                        @if ($eTicket->rejectedBy)
                                                            <tr>
                                                                <td>Decline</td>
                                                                <td style="text-align: left;">
                                                                    {{ $eTicket->rejectedBy->name_en ?? '' }}
                                                                    
                                                                    <br>

                                                                    @if ($eTicket->remarks)
                                                                        **Remarks: {{ $eTicket->remarks }}
                                                                    @endif
                                                                </td>

                                                                <td>{{ date('d M, Y', strtotime($eTicket->rejected_at)) }} at {{ date('h:i a', strtotime($eTicket->rejected_at)) }}</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>    
                                    @endcan    
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
    <script>
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush