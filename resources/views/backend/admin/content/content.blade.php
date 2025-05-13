@foreach($contents as $content)
    <div class="col-md-3 mb-4">
        <div class="content-card">
            <img src="{{ $content->thumbnail ? Storage::url($content->thumbnail) : asset('images/placeholder.jpg') }}" alt="{{ $content->content_name }}">

            <div class="action-icons">
                <i class="fas fa-heart {{ $content->is_favorite ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleFavorite(this)"></i>
                <i class="fas fa-bookmark {{ $content->is_saved ? 'active' : '' }}" data-id="{{ Crypt::encryptString($content->id) }}" onclick="toggleSave(this)"></i>
            </div>

            <div class="card-body">
                <h5 class="card-title">{{ Str::limit($content->content_name, 50) }}</h5>
                
                <div class="card-meta">
                    <p>By: {{ $content->createdBy->name ?? 'Unknown' }}</p>

                    @if ($content->content_type == 'Video')
                        <p>Type: {{ $content->content_type }}</p>
                    @elseif ($content->content_type == 'PDF')
                        <p>Type: {{ $content->content_type }}</p>
                    @elseif ($content->content_type == 'Article')
                        <p>Type: {{ $content->content_type }}</p>
                    @elseif ($content->content_type == 'Audio')
                        <p>Type: {{ $content->content_type }}</p>
                    @elseif ($content->content_type == 'Image')
                        <p>Type: {{ $content->content_type }}</p>
                    @else
                        <p>Type: Other</p>
                    @endif

                    <p>{{ date('d M, Y h:i A', strtotime($content->published_at)) }}</p>
                </div>
            </div>
        </div>
    </div>
@endforeach