{{-- Header section --}}
@include('backend.layouts.head')

    <!-- Begin page -->
    <div id="layout-wrapper">
        {{-- <div class="bbs-loader-wrapper" id="loader-1">
            <div id="loader" style="background-image: url({{ asset('loader.png') }}); background-repeat: no-repeat; background-position: center;"></div>
        </div> --}}

        {{-- Top bar section --}}
        @include('backend.layouts.topbar')

        <!-- ========== App Menu ========== -->
        {{-- Sidebar section --}}
        @include('backend.layouts.sidebar')

        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('content')

        {{-- Footer section --}}
        @include('backend.layouts.footer')