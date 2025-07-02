@extends('backend.layouts.app')

@section('title', 'My Contents | '.($global_setting->title ?? ""))

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
                                            <option value="Link">Link</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    @if (Auth::user()->role_id != 5)
                                        <div class="col-md-2 col-sm-6">
                                            <select name="status" id="statusId" class="form-control select2">
                                                <option value="">--Search by Status--</option>
                                                
                                                <option value="0">Unpublished</option>
                                                <option value="1">Published</option>
                                                <option value="3">Archived</option>
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex gap-2">
                                            <input type="text" name="from_date" id="from_date" class="form-control flatpickr" placeholder="From Date">
                                            <input type="text" name="to_date" id="to_date" class="form-control flatpickr" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <input type="text" class="form-control" id="content_name" name="content_name" placeholder="Search by Content Name">
                                    </div>

                                    <div class="col-md-1 col-sm-6">
                                        <select name="per_page" id="per_page" class="form-control select2">
                                            <option value="12">12</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-sm-4">
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

        let currentCategoryId = null;
        let fetchTimer;
        let isFetching = false;

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
                debounceFetchFilteredData(1, false);
            });

            let typingTimer;
            $('#content_name').on('keyup', function() {
                clearTimeout(typingTimer);
                
                typingTimer = setTimeout(() => debounceFetchFilteredData(1, false), 500);
            });

            $('#resetButton').on('click', function() {
                $('#filterForm')[0].reset();
                $('#content_name').val('');
                $('#content_type').val('').trigger('change');
                $('#statusId').val('').trigger('change');
                $('#from_date').val('');
                $('#to_date').val('');
                $('#per_page').val('12').trigger('change');
                $('.category-item').removeClass('selected active');
                currentCategoryId = null;
                $('#filterForm').find('input[name="category_id"]').remove();
                $('#loadMore').data('page', 1).show();

                debounceFetchFilteredData(1, false);
            });

            $('#loadMore').on('click', function() {
                let page = $(this).data('page') || 1;
                
                page++;
                
                debounceFetchFilteredData(page, true);
                
                $(this).data('page', page);
            });

            $(document).off('click', '.category-item').on('click', '.category-item', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const categoryId = $(this).data('id');
                const categoryName = $(this).data('category-name');

                $('.category-item').removeClass('selected active');

                $(this).addClass('selected active');

                currentCategoryId = categoryId;

                debounceFetchFilteredData(1, false, categoryId);
            });
        });

        function debounceFetchFilteredData(page, append, categoryId = null) {
            clearTimeout(fetchTimer);

            fetchTimer = setTimeout(() => {
                if (!isFetching) {
                    fetchFilteredData(page, append, categoryId);
                }
            }, 100);
        }

        function fetchFilteredData(page = 1, append = false, categoryId = null) {
            if (isFetching) return;

            isFetching = true;

            let formData = $('#filterForm').serializeArray();
            let data = { page: page };

            formData.forEach(item => {
                if (item.value && item.name !== 'category_id') {
                    data[item.name] = item.value;
                }
            });

            if (categoryId !== null) {
                data.category_id = categoryId;
                currentCategoryId = categoryId;
            } else if (currentCategoryId !== null && page === 1) {
                data.category_id = currentCategoryId;
            }

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
                        $('#loadMore').toggle(response.hasMore);
                    } else {
                        toastr.error(response.message || 'Failed to fetch content.', 'Error');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                },
                complete: function() {
                    isFetching = false;
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
                        type: "POST",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });

                                setTimeout(() => window.location.reload(), 1000);
                            }
                        }
                    });
                }
            });
        }

        function publishContent(id) {
            $.ajax({
                url: "{{ route('admin.content.publish', ':id') }}".replace(':id', id),
                type: function(response) {
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

        function archiveContent(id) {
            $.ajax({
                url: "{{ route('admin.content.archive', ':id') }}".replace(':id', id),
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
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