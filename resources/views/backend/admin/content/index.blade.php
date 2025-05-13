@extends('backend.layouts.app')

@section('title', 'Content List | '.($global_setting->title ?? ""))

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet" />

    <style>
        .hamburger-menu {
            position: relative;
            width: 100%;
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
            /* padding: 10px 0; */
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
        }
        .category-item:hover {
            background: #f1f5f9;
        }
        .category-item i {
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
            padding: 10px 0;
            border: 1px solid #e5e7eb;
        }
        .category-item:hover > .sub-menu {
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
            height: 150px;
            overflow: hidden;
        }
        .content-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content-card .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            padding: 10px;
        }
        .content-card:hover .overlay {
            opacity: 1;
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
        .content-card .action-icons i:hover {
            color: #93c5fd;
        }
        .content-card .action-icons .active {
            color: #f87171;
        }
        .content-card .card-body {
            padding: 15px;
            border-top: 1px solid #f3f4f6;
        }
        .content-card .card-title {
            font-size: 1rem;
            margin-bottom: 8px;
            color: #1f2937;
            font-weight: 600;
        }
        .content-card .card-meta {
            font-size: 0.85rem;
            color: #6b7280;
        }
        .content-card .card-meta p {
            margin-bottom: 4px;
        }
        .dropdown-menu {
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 8px 0;
        }
        .dropdown-item {
            padding: 8px 20px;
            font-size: 0.9rem;
            color: #374151;
        }
        .dropdown-item:hover {
            background: #f1f5f9;
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
            <div class="row mb-4">
                <div class="col-12">
                     <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active" aria-current="page">Content List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('backend.admin.partials.alert')

                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Content List</h4>

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
                                                <span><i class="fas fa-bars me-2"></i> Categories</span>
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <div class="category-menu" id="categoryMenu">
                                                @foreach($categories->where('parent_id', null) as $category)
                                                    @include('backend.admin.content.categoryMenu', ['category' => $category])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <select name="content_id" id="content_id" class="form-control select2">
                                            <option value="">--Search by Content--</option>
                                            @foreach($contents as $content)
                                                <option value="{{ $content->id }}">{{ $content->content_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <select name="content_type" id="content_type" class="form-control select2">
                                            <option value="">--Search by Type--</option>
                                            
                                            <option value="Video">Video</option>
                                            <option value="PDF">PDF</option>
                                            <option value="Article">Article</option>
                                            <option value="Audio">Audio</option>
                                            <option value="Image">Image</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex gap-2">
                                            <input type="text" name="from_date" id="from_date" class="form-control flatpickr" placeholder="From Date">
                                            <input type="text" name="to_date" id="to_date" class="form-control flatpickr" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <button type="button" class="btn btn-danger w-100" id="resetButton">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="row" id="contentContainer">
                                @foreach($contents as $content)
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="content-card">
                                            <div class="thumbnail-wrapper">
                                                <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : asset('images/placeholder.jpg') }}" alt="{{ $content->content_name }}">

                                                <div class="overlay">
                                                    <div class="action-icons">
                                                        <i class="fas fa-heart {{ $content->is_favorite ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleFavorite(this)"></i>

                                                        <i class="fas fa-bookmark {{ $content->is_saved ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleSave(this)"></i>

                                                        <div class="dropdown">
                                                            <i class="fas fa-ellipsis-v" data-bs-toggle="dropdown" aria-expanded="false"></i>

                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="{{ route('admin.content.edit', Crypt::encryptString($content->id)) }}">Edit</a></li>

                                                                <li><a class="dropdown-item" href="#" onclick="deleteContent('{{ Crypt::encryptString($content->id) }}')">Delete</a></li>

                                                                <li><a class="dropdown-item" href="#" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Archive</a></li>

                                                                <li><a class="dropdown-item" href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}">Show Details</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <h5 class="card-title">{{ Str::limit($content->content_name, 50) }}</h5>

                                                <div class="card-meta">
                                                    <p>By: {{ $content->createdBy->name ?? 'Unknown' }}</p>
                                                    <p>Type: {{ $content->content_type ?? 'Unknown' }}</p>
                                                    <p>{{ date('d M, Y h:i A', strtotime($content->published_at)) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($contents->hasMorePages())
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

            $('#resetButton').on('click', function() {
                $('#content_id').val('').trigger('change');
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

            $('.category-item').on('click', function(e) {
                e.preventDefault();
                $('.category-item').removeClass('selected');
                $(this).addClass('selected');
                let categoryId = $(this).data('id');
                let input = $('<input>').attr({
                    type: 'hidden',
                    name: 'category_id',
                    value: categoryId
                });
                $('#filterForm').find('input[name="category_id"]').remove();
                $('#filterForm').append(input);
                fetchFilteredData(1);
            });
        });

        function fetchFilteredData(page = 1, append = false) {
            let data = $('#filterForm').serialize() + '&page=' + page;
            $.ajax({
                url: "{{ route('admin.content.index') }}",
                type: "GET",
                data: data,
                success: function(response) {
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
                },
                error: function(xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
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
                        $(element).toggleClass('active');
                        Swal.fire("Success!", response.message, "success");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
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
                        $(element).toggleClass('active');
                        Swal.fire("Success!", response.message, "success");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
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
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            if (response.success) {
                                fetchFilteredData(1);
                                Swal.fire("Deleted!", response.message, "success");
                            }
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        }

        function archiveContent(id) {
            $.ajax({
                url: "{{ route('admin.content.archive', ':id') }}".replace(':id', id),
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        fetchFilteredData(1);
                        Swal.fire("Success!", response.message, "success");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
                }
            });
        }
    </script>
@endpush