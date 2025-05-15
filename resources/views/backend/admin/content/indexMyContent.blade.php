@extends('backend.layouts.app')

@section('title', 'My Contents | '.($global_setting->title ?? ""))

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet" />
    <style>
        .hamburger-menu {
            position: relative;
            width: 100%;
        }
        .hamburger-menu, .category-menu {
            overflow: visible !important;
        }
        .category-toggle {
            width: 100%;
            padding: 10px 15px;
            border-radius: 6px;
            background: #f8fafc;
            border: 1px solid #d1d5db;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            color: #374151;
        }
        .category-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            border: 1px solid #e5e7eb;
        }
        .category-menu.show {
            display: block;
        }
        .category-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s;
            font-size: 0.9rem;
            color: #374151;
            position: relative;
            cursor: pointer;
        }
        .category-item:hover {
            background: #f1f5f9;
        }
        .category-item span i {
            margin-right: 10px;
            color: #6b7280;
        }
        .category-item .arrow {
            font-size: 0.8rem;
            color: #6b7280;
        }
        .sub-menu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            border: 1px solid #e5e7eb;
            z-index: 1001;
        }
        .category-item:hover > .sub-menu {
            display: block !important;
        }
        .sub-menu:hover {
            display: block;
        }
        .content-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .content-card:hover {
            transform: translateY(-5px);
        }
        .content-card .thumbnail-wrapper {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
        }
        .content-card img {
            width: 100%;
            height: 100%;
        }
        .content-card .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            opacity: 1;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            padding: 10px;
        }
        .content-card .action-icons {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .content-card .action-icons i {
            cursor: pointer;
            color: #fff;
            font-size: 1.1rem;
            transition: color 0.2s;
        }
        .content-card .action-icons .active {
            color: #71f8f3;
        }
        .content-card .card-body {
            padding: 10px;
            border-top: 1px solid #f3f4f6;
        }
        .content-card .card-title {
            font-size: 1rem;
            margin-bottom: 8px;
            color: #1f2937;
            font-weight: 600;
        }
        .content-card .card-meta {
            font-size: 0.7rem;
            color: #6b7280;
        }
        .content-card .card-meta p {
            margin-bottom: 4px;
        }
        .dropdown-menu {
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            background: linear-gradient(90deg, #0F2027, #203A43, #2C5364) !important;
            padding: 0;
        }
        .dropdown-item {
            padding: 8px 20px;
            font-size: 0.9rem;
            color: #fff;
            transition: ease-in 0.2s all;
        }
        .dropdown-item:hover {
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
            background: none;
            color: #fff;
        }
        .load-more-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 30px;
            background: #2563eb;
            color: #fff;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .load-more-btn:hover {
            background: #1d4ed8;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">My Contents</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">My Contents</h4>
                            <div class="flex-shrink-0">
                                @can('create_content')
                                    <a class="btn btn-primary" href="{{ route('admin.content.create') }}">
                                        Add New Content
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form id="filterForm">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="hamburger-menu">
                                            <button type="button" class="category-toggle" id="categoryToggle">
                                                <span><i class="las la-bars me-2"></i> Categories</span>
                                            </button>

                                            <div class="category-menu" id="categoryMenu">
                                                @foreach($categories->where('parent_id', null) as $category)
                                                    @include('backend.admin.content.categoryMenu', ['category' => $category])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <select name="content_type" id="content_type" class="form-control select2">
                                            <option value="">--Search by Type--</option>
                                            <option value="Video">Video</option>
                                            <option value="PDF">PDF</option>
                                            <option value="Audio">Audio</option>
                                            <option value="Image">Image</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex gap-2">
                                            <input type="text" name="from_date" id="from_date" class="form-control flatpickr" placeholder="From Date">
                                            <input type="text" name="to_date" id="to_date" class="form-control flatpickr" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <input type="text" class="form-control" id="content_name" name="content_name" placeholder="Search by Content Name">
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <button type="button" class="btn btn-danger w-100" id="resetButton">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="row" id="contentContainer">
                                @include('backend.admin.content.content', ['contents' => $contents])
                            </div>

                            @if ($contents->hasMorePages())
                                <button class="load-more-btn" id="loadMore">Load More</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };

        $(document).ready(function() {
            $('.flatpickr').flatpickr({
                dateFormat: 'Y-m-d',
            });

            $('#categoryToggle').on('click', function() {
                $('#categoryMenu').toggleClass('show');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.hamburger-menu').length) {
                    $('#categoryMenu').removeClass('show');
                }
            });

            $('#filterForm').on('change', 'select, input', function() {
                fetchFilteredData(1);
            });

            let typingTimer;

            $('#content_name').on('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => fetchFilteredData(1), 500);
            });

            $('#resetButton').on('click', function() {
                $('#filterForm')[0].reset();
                $('#content_name').val('');
                $('#content_type').val('').trigger('change');
                $('#from_date').val('');
                $('#to_date').val('');
                $('.category-item').removeClass('selected');
                $('input[name="category_id"]').remove();

                fetchFilteredData(1);
            });

            $('#loadMore').on('click', function() {
                let page = $(this).data('page') || 1;
                page++;
                fetchFilteredData(page, true);
                $(this).data('page', page);
            });

            $(document).on('click', '.category-item', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let categoryId = $(this).data('id');

                $('#content_name').val('');
                $('#content_type').val('').trigger('change');
                $('#from_date').val('');
                $('#to_date').val('');
                
                setTimeout(() => {
                    $('.category-item').removeClass('selected');

                    $(this).addClass('selected');

                    fetchFilteredData(1, false, categoryId);
                }, 200);
            });
        });

        function fetchFilteredData(page = 1, append = false, categoryId = null) {
            let data = categoryId ? { category_id: categoryId, page: page } : $('#filterForm').serialize() + '&page=' + page;

            $.ajax({
                url: "{{ route('admin.content.indexMyContent') }}",
                type: "GET",
                data: data,
                success: function(response) {
                    if (response.success) {
                        if (append) {
                            $('#contentContainer').append(response.html);
                        } else {
                            $('#contentContainer').html(response.html);
                        }
                        if (!response.hasMore) {
                            $('#loadMore').hide();
                        } else {
                            $('#loadMore').show();
                        }
                    } else {
                        toastr.error(response.message || 'Failed to fetch content.', 'Error');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        }

        function toggleFavorite(element) {
            let id = $(element).data('id');
            $.ajax({
                url: "{{ route('admin.content.toggleFavorite', ':id') }}".replace(':id', id),
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        $(element).toggleClass('lar la-heart las la-heart');
                        $(element).toggleClass('active');
                        toastr.success(response.message, 'Success');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        }

        function toggleSave(element) {
            let id = $(element).data('id');
            $.ajax({
                url: "{{ route('admin.content.toggleSave', ':id') }}".replace(':id', id),
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        $(element).toggleClass('las la-bookmark lar la-bookmark');
                        $(element).toggleClass('active');
                        toastr.success(response.message, 'Success');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        }

        function deleteContent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.content.destroy', ':id') }}".replace(':id', id),
                        type: "GET",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                        }
                    });
                }
            });
        }

        function archiveContent(id) {
            $.ajax({
                url: "{{ route('admin.content.archive', ':id') }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: response.message,
                            icon: 'success',
                            showCancelButton: false,
                        });
                        setTimeout(() => window.location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        }
    </script>
@endpush