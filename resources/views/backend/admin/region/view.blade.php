@extends('backend.layouts.app')

@section('title', 'Division Details | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        {{-- <h4 class="mb-sm-0">{{ __('pages.Division Details') }}</h4> --}}

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('messages.Dashboard') }}</a></li>

                                <li class="breadcrumb-item active">{{ __('pages.Division Details') }}</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">{{ __('pages.Division Details') }} (Location)</h4>
                        </div>
                        <!-- end card header -->

                        <div class="card-body">
                            <table class="table table-bordered table-sm table-striped m-auto">
                                <tbody>
                                    {{-- <tr>
                                        <td>Order SL:</td>

                                        <td>{{ $region->sl }}</td>
                                    </tr> --}}

                                    <tr>
                                        <td>{{ __('pages.Division Name') }}:</td>

                                        <td>{{ $region->name_en }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('pages.Status') }}:</td>

                                        <td>
                                            @if ($region->status == 1)
                                                <span class="badge bg-success">{{ __('pages.Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('pages.Inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            {{ __('pages.Created By') }}
                                        </td>

                                        <td>
                                            {{ $region->createdBy->name_en ?? '' }} at {{ date('d M, Y', strtotime($region->created_at)) }}
                                        </td>
                                    </tr>

                                    @if ($region->updated_by)
                                        <tr>
                                            <td>
                                                {{ __('pages.Updated By') }}
                                            </td>

                                            <td>
                                                {{ $region->updatedBy->name_en ?? '' }} at {{ date('d M, Y', strtotime($region->updated_at)) }}
                                            </td>
                                        </tr>
                                    @endif
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
