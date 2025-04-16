@extends('backend.layouts.app')
@section('title', ''.($global_setting->title ?? "").' | ইউনিয়ন বিস্তারিত')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">ইউনিয়ন বিস্তারিত</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">ইউনিয়ন বিস্তারিত</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">ইউনিয়ন বিস্তারিত</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <table class="table table-bordered table-sm table-striped m-auto" style="max-width: 600px">
                        <tbody>
                            <tr>
                                <td>Order SL:</td>
                                <td>{{$union->sl}}</td>
                            </tr>
                            <tr>
                                <td>নাম:</td>
                                <td>{{$union->name}}</td>
                            </tr>

                            <tr>
                                <td>অঞ্চল:</td>
                                <td>{{$union->upazilaInfo->districtInfo->divisionInfo->name ?? '-'}}</td>
                            </tr>
                            <tr>
                                <td>জেলা:</td>
                                <td>{{$union->upazilaInfo->districtInfo->name ?? '-'}}</td>
                            </tr>
                            <tr>
                                <td>উপজেলা:</td>
                                <td>{{$union->upazilaInfo->name ?? '-'}}</td>
                            </tr>
                            <tr>
                                <td>স্ট্যাটাস:</td>
                                <td>
                                    @if ($union->status == 1)
                                        <span class="badge bg-success">সক্রিয়</span>
                                    @else
                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    প্রস্তুতকারি
                                </td>
                                <td>
                                    {{$union->createdBy->full_name ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    আপডেট করেছেন
                                </td>
                                <td>
                                    {{$union->updatedBy->full_name ?? ''}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        $('[href*="{{$menu_expand}}"]').addClass('active');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{$menu_expand}}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded','true');
        $('[href*="{{$menu_expand}}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
    </script>
@endpush
