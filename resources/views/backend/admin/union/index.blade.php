@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | ইউনিয়ন লিস্ট')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">ইউনিয়ন লিস্ট</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">ইউনিয়ন লিস্ট</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">ইউনিয়ন লিস্ট</h4>
                        <div class="flex-shrink-0">
                            @can('add_union')
                                <a href="{{route('admin.union.create')}}" class="btn btn-primary">
                                    নতুন ইউনিয়ন যোগ করুন
                                </a>
                            @endcan

                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-2">
                                    <div>
                                        <select class="form-control" data-choices data-choices-search-false name="division" id="idStatus">
                                            <option value="">অঞ্চল নির্বাচন করুন</option>
                                            @foreach ($regions as $region)
                                                    <option @if(isset($_GET['division']) and $_GET['division']==$region->id) selected @endif value="{{$region->id}}">{{$region->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-2 col-sm-6">
                                    <div class="search-box">
                                        <input type="text" class="form-control search" name="name" placeholder="ইউনিয়ন name" @if(isset($_GET['name']) and $_GET['name']!='') value="{{$_GET['name']}}" @endif>
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="col-xxl-1 col-sm-4">
                                    <div>
                                        <button style="max-width: 150px;" type="submit" class="btn btn-primary w-100"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                            ফিল্টার
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-2 col-sm-4">
                                    <div>
                                        <a style="max-width: 150px;" href="{{route('admin.union.index')}}" class="btn btn-danger w-100"> <i class="ri-restart-line me-1 align-bottom"></i>
                                            ফিল্টার অপসারণ
                                        </a>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">ক্র.নং</th>
                                        <th>ইউনিয়ন নাম</th>
                                        <th>অঞ্চল</th>
                                        <th>জেলা</th>
                                        <th>উপজেলা</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th>প্রস্তুতকারি</th>
                                        <th class="text-center">অ্যাকশন </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($unions->count() > 0)
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($unions as $union)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>{{$union->name ?? '-'}}</td>
                                                <td>{{$union->upazilaInfo->districtInfo->divisionInfo->name ?? '-'}}</td>
                                                <td>{{$union->upazilaInfo->districtInfo->name ?? '-'}}</td>
                                                <td>{{$union->upazilaInfo->name ?? '-'}}</td>
                                                <td class="text-center">
                                                    @if ($union->status == 1)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td>{{$union->createdBy->full_name ?? '-'}}</td>
                                                <td class="text-center">

                                                    @can('view_union')
                                                        <a href="{{route('admin.union.view',$union->id)}}" title="দেখুন " type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-eye" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('edit_union')
                                                        <a href="{{route('admin.union.edit', $union->id)}}" title="সম্পাদনা" type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-edit" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                    @can('delete_union')
                                                        <a onclick="return confirm('আপনি কি নিশ্চিত?')" href="{{route('admin.union.delete',$union->id)}}" title="মুছে ফেলুন" type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-light">
                                                            <i class="las la-trash" style="font-size: 1.6em;"></i>
                                                        </a>
                                                    @endcan

                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
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
                            <div class="mt-3">
                                {{$unions->appends($_GET)->links()}}
                            </div>
                        </div>
                        <!-- end table responsive -->
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





<!-- Grids in modals -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
    Launch Demo Modal
</button> --}}

@endsection
