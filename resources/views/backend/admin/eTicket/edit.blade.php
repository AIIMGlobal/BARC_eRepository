@extends('backend.layouts.app')

@section('title', ''.($global_setting->title ?? "").' | Edit E-Ticket')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit E-Ticket</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">Edit E-Ticket</li>
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
                            <h4 class="card-title mb-0 flex-grow-1 text-white">State Your Issue</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.eTicket.update', Crypt::encryptString($eTicket->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="box">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div>
                                                <label for="client_id" class="form-label">Authority<span style="color:red;">*</span></label>
                                                
                                                @can('admin_permission')
                                                    <select name="client_id" id="client_id" class="form-control select2" required>
                                                        <option value="">--Select Authority--</option>
        
                                                        @foreach ($clients as $client)
                                                            <option value="{{ $client->id }}" {{ $client->id == $eTicket->client_id ? 'selected' : '' }}>{{ $client->name_en }}</option> 
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="hidden" name="client_id" id="client_id" value="{{ Auth::id() }}">

                                                    <input type="text" value="{{ Auth::user()->name_en }}" class="form-control" readonly>
                                                @endcan
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <div>
                                                <label for="project_id" class="form-label">Project<span style="color:red;">*</span></label>
    
                                                <select name="project_id" id="project_id" class="form-control select2" required>
                                                    <option value="">--Select Project--</option>
    
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}" {{ $project->id == $eTicket->project_id ? 'selected' : '' }}>{{ $project->name }}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mt-4">
                                            <div>
                                                <label for="title" class="form-label">Issue<span style="color:red;">*</span></label>
    
                                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Your Issue" value="{{ $eTicket->title ?? old('title') }}" required>
                                            </div>
                                        </div>
    
                                        <div class="col-md-6 col-sm-12 mt-4">
                                            <div>
                                                <label for="ticket_type_id" class="form-label">E-Ticket Type<span style="color:red;">*</span></label>
    
                                                <select name="ticket_type_id" id="ticket_type_id" class="form-control select2" required>
                                                    <option value="">--Select E-Ticket Type--</option>
    
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}" {{ $type->id == $eTicket->ticket_type_id ? 'selected' : '' }}>{{ $type->title }}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-sm-12 mt-4">
                                            <div>
                                                <label for="description" class="form-label">Describe Your Issue</label>
    
                                                <textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="10">{{ $eTicket->description ?? old('description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mt-4">
                                            <div>
                                                <label for="priority" class="form-label">Priority<span style="color:red;">*</span></label>
    
                                                <select name="priority" id="priority" class="form-control select2" required>
                                                    <option value="">--Select Priority--</option>
    
                                                    @foreach (prioritys() as $index => $priority)
                                                        <option value="{{ $priority }}" {{ $priority == $eTicket->priority ? 'selected' : '' }}>{{ $priority }}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mt-5">
                                            <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                                <label class="form-check-label form-label" for="SwitchCheck_5">Add Attachement?</label>

                                                <input class="form-check-input form-control" type="checkbox" role="switch" name="has_file" id="SwitchCheck_5" value="1" autocomplete="off">
                                            </div>
                                        </div>
                
                                        <div class="col-md-12 col-sm-12 mt-4">
                                            <div class="hidden_area d-none">
                                                <table class="table table-bordered table-sm text-left table-area">
                                                    <thead>
                                                        <tr>
                                                            <th>Documents: <span style="color:red;">*</span></th>
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
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="hstack gap-2">
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
    <script>
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>

    <script>
        $('#client_id').on('change', function(e) {
            e.preventDefault();

            let project_id = $("#project_id");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.projectsByClient') }}",
                data: {_token:$('input[name=_token]').val(),
                client_id: $(this).val()},

                success:function(response){
                    $('option', project_id).remove();
                    $('#project_id').append('<option value="">--Select Project--</option>');
                    $.each(response, function(){
                        $('<option/>', {
                            'value': this.id,
                            'text': this.name
                        }).appendTo('#project_id');
                    });
                }

            });
        });
    </script>

    <script>
        $('#SwitchCheck_5').click(function() {
            var checked = this.checked;

            if (checked) {
                $('.hidden_area').removeClass('d-none');
            } else {
                $('.hidden_area').addClass('d-none');
                $('.table-area').find('input').removeAttr('required');
            }
        });

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