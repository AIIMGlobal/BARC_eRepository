@foreach($contents as $content)
    @php
        $contentFav = $content->userActivities->where('activity_type', 1)->first();
        $contentSave = $content->userActivities->where('activity_type', 2)->first();
    @endphp

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="content-card">
            <div class="thumbnail-wrapper">
                <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : 'https://t4.ftcdn.net/jpg/05/17/53/57/360_F_517535712_q7f9QC9X6TQxWi6xYZZbMmw5cnLMr279.jpg' }}" alt="{{ $content->content_name }}">

                <div class="overlay">
                    <div class="action-icons">
                        {{-- <i class="{{ $contentFav ? 'las la-heart' : 'lar la-heart' }} {{ $contentFav ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleFavorite(this)"></i>

                        <i class="{{ $contentSave ? 'las la-bookmark' : 'lar la-bookmark' }} {{ $contentSave ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleSave(this)"></i> --}}

                        @if (Auth::id() == $content->created_by)
                            <div class="dropdown">
                                <i class="las la-ellipsis-v" data-bs-toggle="dropdown" aria-expanded="false"></i>

                                <ul class="dropdown-menu">
                                    @can('edit_content')
                                        <li><a class="dropdown-item" href="{{ route('admin.content.edit', Crypt::encryptString($content->id)) }}" target="_blank">Edit</a></li>
                                    @endcan

                                    @can('view_content')
                                        <li><a class="dropdown-item" href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}" target="_blank">Show Details</a></li>
                                    @endcan

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
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.content.show', Crypt::encryptString($content->id)) }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $content->content_name }}</h5>

                    <div class="card-meta">
                        <p>By: {{ $content->createdBy->name_en ?? 'Unknown' }}</p>
                        <p>Category: {{ $content->category->category_name ?? '' }}</p>
                        <p>Type: {{ $content->content_type ?? 'Unknown' }}</p>
                        <p>{{ date('d M, Y h:i A', strtotime($content->published_at)) }}</p>

                        @if ($content->status == 0)
                            <p>Status: <span class="badge bg-primary">Unpublished</span></p>
                        @elseif ($content->status == 1)
                            <p>Status: <span class="badge bg-success">Published</span></p>
                        @elseif ($content->status == 3)
                            <p>Status: <span class="badge bg-info">Archived</span></p>
                        @else
                            <p>Status: <span class="badge bg-danger">Undefined</span></p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    </div>
@endforeach