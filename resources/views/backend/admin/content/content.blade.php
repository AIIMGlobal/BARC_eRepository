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
            text-align: justify;
            line-height: 22px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            min-height: 44px;
        }
        .content-card .card-meta {
            font-size: 0.7rem;
            color: #6b7280;
        }
        .content-card .card-meta p {
            margin-bottom: 4px;
        }
        
        .load-more-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 30px;
            color: #fff;
            background: #3DB043;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        .load-more-btn:hover::after {
            left: 0;
        }
        .load-more-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1A2F36, #2D4A53, #3B6476);
            transition: all 0.3s ease;
            z-index: -1;
        }
    </style>
@endpush

@foreach($contents as $content)
    @php
        $contentFav = $content->userActivities->where('activity_type', 1)->first();
        $contentSave = $content->userActivities->where('activity_type', 2)->first();
    @endphp

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="content-card">
            <div class="thumbnail-wrapper">
                <img src="{{ ($content->thumbnail && Storage::exists('public/' . $content->thumbnail)) ? Storage::url($content->thumbnail) : 'https://t4.ftcdn.net/jpg/05/17/53/57/360_F_517535712_q7f9QC9X6TQxWi6xYZZbMmw5cnLMr279.jpg' }}" alt="{{ $content->content_name }}">

                <div class="overlay">
                    <div class="action-icons">
                        <i class="{{ $contentFav ? 'las la-heart' : 'lar la-heart' }} {{ $contentFav ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleFavorite(this)" title="Favorite"></i>

                        <i class="{{ $contentSave ? 'las la-bookmark' : 'lar la-bookmark' }} {{ $contentSave ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleSave(this)" title="Watch Later"></i>

                        <div class="dropdown">
                            <i class="las la-ellipsis-v" data-bs-toggle="dropdown" aria-expanded="false"></i>

                            <ul class="dropdown-menu">
                                @can('view_content')
                                    <li><a class="dropdown-item" href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}" target="_blank">Show Details</a></li>
                                @endcan

                                @if (Auth::id() == $content->created_by || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                    @if ($content->status == 0 && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                                        @can('edit_content')
                                            <li><a class="dropdown-item" href="{{ route('admin.content.edit', Crypt::encryptString($content->id)) }}" target="_blank">Edit</a></li>
                                        @endcan
                                        
                                        @can('can_publish')
                                            <li><button class="dropdown-item" type="button" onclick="publishContent('{{ Crypt::encryptString($content->id) }}')">Publish</button></li>
                                        @endcan
                                    @elseif ($content->status == 0 && Auth::user()->role_id == 3)
                                        @if (($content->createdBy->userInfo->office_id ?? '') == (Auth::user()->userInfo->office_id ?? ''))
                                            @can('edit_content')
                                                <li><a class="dropdown-item" href="{{ route('admin.content.edit', Crypt::encryptString($content->id)) }}" target="_blank">Edit</a></li>
                                            @endcan
                                            
                                            @can('can_publish')
                                                <li><button class="dropdown-item" type="button" onclick="publishContent('{{ Crypt::encryptString($content->id) }}')">Publish</button></li>
                                            @endcan
                                        @endif
                                    @endif

                                    @can('archive_content')
                                        @if ($content->status == 3)
                                            <li><button class="dropdown-item" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Unarchive</button></li>
                                        @else
                                            <li><button class="dropdown-item" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Archive</button></li>
                                        @endif
                                    @endcan

                                    @can('delete_content')
                                        <li><button class="dropdown-item" type="button" onclick="deleteContent('{{ Crypt::encryptString($content->id) }}')">Delete</button></li>
                                    @endcan
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}" target="_blank">
                <div class="card-body">
                    <h5 class="card-title" title="{{ $content->content_name }}">{{ $content->content_name }}</h5>

                    <div class="card-meta">
                        <p>By: {{ $content->createdBy->name_en ?? 'Unknown' }}</p>
                        <p>Category: {{ $content->category->category_name ?? '' }}</p>
                        <p>Type: {{ $content->content_type ?? 'Unknown' }}</p>

                        @if ($content->status == 1)
                            <p>Publish on: {{ date('d M, Y h:i A', strtotime($content->published_at)) }}</p>
                        @else
                            <p>Submitted on: {{ date('d M, Y h:i A', strtotime($content->created_at)) }}</p>

                            @if ($content->updated_by)
                                <p>Updated on: {{ date('d M, Y h:i A', strtotime($content->updated_at)) }}</p>
                            @endif
                        @endif

                        @if ($content->status == 0)
                            <p>Status: <span class="badge bg-danger">Unpublished</span></p>
                        @elseif ($content->status == 1)
                            <p>Status: <span class="badge bg-success">Published</span></p>
                        @elseif ($content->status == 3)
                            <p>Status: <span class="badge bg-primary">Archived</span></p>
                        @else
                            <p>Status: <span class="badge bg-danger">Undefined</span></p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    </div>
@endforeach