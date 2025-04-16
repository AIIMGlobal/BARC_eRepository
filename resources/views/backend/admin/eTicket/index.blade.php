@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | E-Ticket List')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">E-Ticket List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">E-Ticket List</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">E-Ticket List</h4>

                            <div class="flex-shrink-0">
                                @can('add_eTicket')
                                    <a class="btn btn-primary" href="{{ route('admin.eTicket.create') }}">
                                        Create New E-Ticket
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
                                            <input @if(isset($_GET['ticket_no']) && $_GET['ticket_no'] != '') value="{{ $_GET['ticket_no'] }}" @endif type="text" class="form-control search" name="ticket_no" placeholder="Search by E-Ticket no.">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    @can('admin_permission')
                                        <div class="col-md-3 col-sm-6">
                                            <div class="search-box">
                                                <select name="client_id" id="client_id" class="form-control select2" autocomplete="off">
                                                    <option value="">--Search by Authority--</option>

                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}" {{ isset($_GET['client_id']) && $_GET['client_id'] == $client->id ? 'selected' : '' }}>{{ $client->name_en }}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endcan

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="project_id" id="project_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Project--</option>

                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}" {{ isset($_GET['project_id']) && $_GET['project_id'] == $project->id ? 'selected' : '' }}>{{ $project->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="ticket_type_id" id="ticket_type_id" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by E-Ticket Type--</option>

                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}" {{ isset($_GET['ticket_type_id']) && $_GET['ticket_type_id'] == $type->id ? 'selected' : '' }}>{{ $type->title }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="search-box">
                                            <select name="priority" id="priority" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Priority--</option>

                                                @foreach (prioritys() as $index => $priority)
                                                    <option value="{{ $priority }}" {{ isset($_GET['priority']) && $_GET['priority'] == $priority ? 'selected' : '' }}>{{ $priority }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <select name="ticket_status" id="ticket_status" class="form-control select2" autocomplete="off">
                                                <option value="">--Search by Status--</option>

                                                @foreach (ticketStatuses() as $index => $ticket_status)
                                                    <option value="{{ $index }}" {{ isset($_GET['ticket_status']) && $_GET['ticket_status'] == $index ? 'selected' : '' }}>{{ $ticket_status }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['from_date']) && $_GET['from_date'] != '') value="{{ $_GET['from_date'] }}" @endif type="date" class="form-control search" name="from_date" placeholder="From Date">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
        
                                    <div class="col-md-2 col-sm-6">
                                        <div class="search-box">
                                            <input @if(isset($_GET['to_date']) && $_GET['to_date'] != '') value="{{ $_GET['to_date'] }}" @endif type="date" class="form-control search" name="to_date" placeholder="To Date">
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
                                            <a style="max-width: 150px;" href="{{ route('admin.eTicket.index') }}" class="btn btn-danger w-100"> 
                                                <i class="ri-restart-line me-1 align-bottom"></i>Reset
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">E-Ticket No.</th>
                                            <th>E-Ticket Type</th>
                                            <th>Title</th>
                                            <th>Authority Name</th>
                                            <th>Project Title</th>
                                            <th class="text-center">Priority</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($eTickets->count() > 0)
                                            @php
                                                $i = ($eTickets->perPage() * ($eTickets->currentPage() - 1) +1);
                                            @endphp

                                            @foreach ($eTickets as $eTicket)
                                                <tr>
                                                    <td class="text-center">#{{ $eTicket->ticket_no }}</td>
                                                    <td>{{ $eTicket->type->title ?? '-' }}</td>

                                                    <td>{{ $eTicket->title ?? '-' }}</td>
                                                    
                                                    <td>{{ $eTicket->client->name_en ?? '-' }}</td>
                                                    <td>{{ $eTicket->project->name ?? '-' }}</td>

                                                    <td class="text-center">{{ $eTicket->priority ?? '-' }}</td>
                                                    
                                                    <td class="text-center">
                                                        @foreach (ticketStatuses() as $index => $ticketStatus)
                                                            @if ($eTicket->status == $index)
                                                                <span class="badge bg-info">{{ $ticketStatus }}</span>
                                                            @endif
                                                        @endforeach
                                                    </td>

                                                    <td class="text-center">
                                                        @can('show_eTicket')
                                                            <a href="{{ route('admin.eTicket.show', Crypt::encryptString($eTicket->id)) }}" title="View" type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                                <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                            </a>
                                                        @endcan

                                                        @if ($eTicket->status == 0)
                                                            @can('edit_eTicket')
                                                                <a href="{{ route('admin.eTicket.edit', Crypt::encryptString($eTicket->id)) }}" title="Edit" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                    <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @if (Auth::user()->user_type != 7)
                                                            @can('delete_eTicket')
                                                                <a onclick="return confirm('Are you sure, you want to delete ?')" href="{{ route('admin.eTicket.delete', Crypt::encryptString($eTicket->id)) }}" title="Delete" class="btn btn-sm btn-danger btn-icon waves-effect waves-light">
                                                                    <i class="las la-times-circle" style="font-size: 1.6em;"></i>
                                                                </a>
                                                            @endcan
                                                        @endif
                                                    </td>
                                                </tr>

                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
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
                            {{ $eTickets->links() }}
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