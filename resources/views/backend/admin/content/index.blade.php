@extends('backend.layouts.app')

@section('title', 'Contents | '.($global_setting->title ?? ""))

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
                                <li class="breadcrumb-item active" aria-current="page">Contents</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Contents</h4>

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
                url: "{{ route('admin.content.index') }}",
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