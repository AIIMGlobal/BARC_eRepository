@extends('backend.layouts.app')

@section('title', 'Dashboard | '.($global_setting->title ?? ""))

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                @include('backend.index_include')
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/pages/widgets.init.js') }}"></script>
@endpush