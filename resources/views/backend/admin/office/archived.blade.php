@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | আর্কাইভ অফিস লিস্ট')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">আর্কাইভ অফিস লিস্ট</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">আর্কাইভ অফিস লিস্ট</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

            <div class="col-xxl-12">

                @include('backend.admin.partials.alert')

                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">আর্কাইভ অফিস লিস্ট</h4>
                        <div class="flex-shrink-0">
                            {{-- @can('add_office')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                    নতুন অফিস যোগ করুন
                                </button>
                            @endcan --}}
                            <a class="btn btn-primary" href="{{route('admin.office.index')}}">
                                অফিসের লিস্ট
                            </a>

                        </div>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">ক্র.নং</th>
                                        <th>অফিসের নাম</th>
                                        <th>অঞ্চল</th>
                                        <th>জেলা</th>
                                        <th>উপজেলা</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>প্রস্তুতকারি</th>
                                        <th>প্রস্তুতির সময়   </th>
                                        <th class="text-center">অ্যাকশন </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($offices->count() > 0)
                                        @php
                                            $i = ($offices->perPage() * ($offices->currentPage() - 1) +1);
                                        @endphp
                                        @foreach ($offices as $office)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$office->name ?? '-'}}</td>
                                                <td>{{$office->division->name ?? '-'}}</td>
                                                <td>{{$office->district->name ?? '-'}}</td>
                                                <td>{{$office->upazila->name ?? '-'}}</td>
                                                <td>
                                                    @if ($office->status == 0)
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @elseif($office->status == 1)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-warning">আর্কাইভ</span>
                                                    @endif
                                                </td>
                                                <td>{{$office->createdUser ? $office->createdUser->full_name : '-'}}</td>
                                                <td>
                                                    {{$office->created_at->format('d-m-Y')}}
                                                </td>
                                                <td class="text-center">
                                                    @can('view_office')
                                                        <a href="{{route('admin.office.show', $office->id)}}" title="দেখুন " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('edit_office')
                                                        <button title="সম্পাদনা" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editOffice{{$office->id}}">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </button>
                                                    @endcan

                                                    {{-- <a onclick="return confirm('আপনি কি নিশ্চিত?')" href="#" title="Delete" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                        <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                    </a> --}}

                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                            @endphp



                                            <div class="modal fade" id="editOffice{{$office->id}}" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalgridLabel">অফিস সম্পাদনা</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{route('admin.office.update', $office->id)}}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row g-3">

                                                                    <div class="col-12">
                                                                        <div>
                                                                            <label for="নাম" class="form-label">অফিসের নাম <span style="color:red;">*</span></label>
                                                                            <input type="text" class="form-control" name="name" value="{{$office->name}}" required>
                                                                        </div>
                                                                    </div><!--end col-->

                                                                    <div class="col-lg-12">
                                                                        <label for="division" class="form-label">অঞ্চল<span style="color:red;">*</span></label>
                                                                        <select name="division" class="form-control" id="division" required>
                                                                            <option value="{{$office->division_id}}">{{$office->division ? $office->division->name : '-'}}</option>
                                                                            @php
                                                                                $editDivision = App\Models\Division::where('id', '!=', $office->division_id)->where('status',1)->get();
                                                                            @endphp
                                                                            @foreach ($editDivision as $division)
                                                                                <option value="{{$division->id}}">{{$division->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div><!--end col-->

                                                                    <div class="col-lg-12">
                                                                        <label for="district" class="form-label">জেলা</label>
                                                                        <select name="district" class="form-control" id="district">
                                                                            <option value="{{$office->district_id}}">{{$office->district ? $office->district->name : '-'}}</option>
                                                                            <option value="">--জেলা নির্বাচন করুন--</option>
                                                                            @php
                                                                                $editDistrict = App\Models\District::where('id', '!=', $office->district_id)->get();
                                                                            @endphp
                                                                            @foreach ($editDistrict as $district)
                                                                                <option value="{{$district->id}}">{{$district->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div><!--end col-->

                                                                    <div class="col-lg-12">
                                                                        <label for="upazila" class="form-label">উপজেলা</label>
                                                                        <select name="upazila" class="form-control" id="upazila" >
                                                                            <option value="{{$office->upazila_id}}">{{$office->upazila ? $office->upazila->name : '-'}}</option>
                                                                            <option value="">--উপজেলা নির্বাচন করুন--</option>
                                                                            @php
                                                                                $editUpazila = App\Models\Upazila::where('id', '!=', $office->upazila_id)->get();
                                                                            @endphp
                                                                            @foreach ($editUpazila as $upazila)
                                                                                <option value="{{$upazila->id}}">{{$upazila->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div><!--end col-->

                                                                    

                                                                    <div class="col-lg-12">
                                                                        <div>
                                                                            <label class="form-check-label" for="statusOption">স্ট্যাটাস</label><span style="color:red;">*</span>
                                                                            <select class="form-select form-control" name="status" required>
                                                                                <option value="">--স্ট্যাটাস নির্বাচন করুন--</option>
                                                                                <option value="0" @if ($office->status == 0) selected @endif>নিষ্ক্রিয়</option>
                                                                                <option value="1" @if ($office->status == 1) selected @endif>সক্রিয়</option>
                                                                                <option value="2" @if ($office->status == 2) selected @endif>আর্কাইভ</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12">
                                                                        <div class="hstack gap-2 justify-content-end">
                                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল করুন</button>
                                                                            <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                                                                        </div>
                                                                    </div>
                                                                </div><!--end row-->
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="100%" class="text-center"><b>কোন তথ্য পাওয়া যায়নি</b></td>
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
                        {{$offices->links()}}
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





<!-- Grids in modals -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
    Launch Demo Modal
</button> --}}
<div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">নতুন অফিস যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.office.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        <div class="col-12">
                            <div>
                                <label for="name" class="form-label">অফিসের নাম <span style="color:red;">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" placeholder=" অফিসের নাম" required>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <label for="division" class="form-label">অঞ্চল<span style="color:red;">*</span></label>
                            <select name="division" class="form-control" id="division" required>
                                <option value="">--অঞ্চল নির্বাচন করুন--</option>
                                @foreach ($divisions as $division)
                                    <option value="{{$division->id}}">{{$division->name}}</option>
                                @endforeach
                            </select>
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <label for="district" class="form-label">জেলা</label>
                            <select name="district" class="form-control" id="district">
                                <option value="">--জেলা নির্বাচন করুন--</option>
                                @foreach ($districts as $district)
                                    <option value="{{$district->id}}">{{$district->name}}</option>
                                @endforeach
                            </select>
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <label for="upazila" class="form-label">উপজেলা</label>
                            <select name="upazila" class="form-control" id="upazila">
                                <option value="">--উপজেলা নির্বাচন করুন--</option>
                                @foreach ($upazilas as $upazila)
                                    <option value="{{$upazila->id}}">{{$upazila->name}}</option>
                                @endforeach
                            </select>
                        </div><!--end col-->
                        
                        <div class="col-lg-12">
                            <div class="form-check form-switch form-switch-custom form-switch-success">
                                <input class="form-check-input" type="checkbox" role="switch" name="status" id="statusOption" checked value="1">
                                <label class="form-check-label" for="statusOption">স্ট্যাটাস</label>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল করুন</button>
                                <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $(".horticultureDiv").hide();
        $(".horticultureOffice").click(function() {
            if($(this).is(":checked")) {
                $(".horticultureDiv").show(300);
                $('#horticulture').attr('required', 'required');
            } else {
                $(".horticultureDiv").hide(200);
                $('#horticulture').removeAttr('required');
            }
        });

        function showHide(that,id) {
            if(that.checked){
                $('.display_option'+id).removeClass('d-none');
            } else {
                $('.display_option'+id).addClass('d-none');
            }
        }
    </script>
@endpush

