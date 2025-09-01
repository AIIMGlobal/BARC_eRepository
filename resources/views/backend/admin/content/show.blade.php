@extends('backend.layouts.app')

@section('title', 'View Content Details | '.($global_setting->title ?? ""))

@push('css')
    <style>
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
        }

        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            outline: none;
        }

        video[controls] {
            display: block !important;
        }

        .pdf-container {
            position: relative;
            width: 100%;
            height: 800px;
            display: flex;
            flex-direction: column;
        }

        .pdf-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            flex-grow: 1;
        }

        .link-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .link-container img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .pdf-fallback, .media-fallback {
            text-align: center;
            padding: 1rem;
            margin: 0;
            color: #fff;
            background: rgba(0, 0, 0, 0.7);
            display: none;
        }

        audio {
            max-width: 100%;
            width: 100%;
            outline: none;
        }

        .relative {
            overflow: visible !important;
        }

        .details-card {
            background: linear-gradient(145deg, #ffffff, #f0f4f8);
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            padding: 10px;
        }

        .detail-label {
            color: #4b5563;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            color: #1f2937;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0;
        }

        .dark .details-card {
            background: linear-gradient(145deg, #1f2937, #374151);
        }

        .dark .detail-label {
            color: #9ca3af;
        }

        .dark .detail-value {
            color: #e5e7eb;
        }

        .media-container {
            margin-bottom: 1.5rem;
        }

        /* Custom Video Player Styles */
        .video_player {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .main-video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 4px solid #fff;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .caption_text {
            position: absolute;
            bottom: 60px;
            width: 100%;
            text-align: center;
            color: #fff;
            background: rgba(0, 0, 0, 0.7);
            padding: 5px;
            display: none;
        }

        .caption_text.active {
            display: block;
        }

        .progressAreaTime {
            position: absolute;
            top: -30px;
            left: var(--x, 0);
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 2px 8px;
            border-radius: 3px;
            display: none;
        }

        .controls {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            padding: 10px;
            transition: opacity 0.3s;
        }

        .controls.active {
            opacity: 1;
        }

        .progress-area {
            width: 100%;
            height: 6px;
            background: #555;
            position: relative;
            cursor: pointer;
        }

        .bufferedBar {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .progress-bar {
            height: 100%;
            background: #3498db;
            position: relative;
        }

        .progress-bar span {
            position: absolute;
            right: 0;
            top: -2px;
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
        }

        .controls-list {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .controls-left, .controls-right {
            display: flex;
            align-items: center;
        }

        .icon {
            margin: 0 5px;
            cursor: pointer;
            color: #fff;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .icon:hover {
            transform: scale(1.1);
            color: #3498db;
        }

        .volume_range {
            width: 80px;
            margin-left: 5px;
            accent-color: #3498db;
        }

        .timer {
            color: #fff;
            margin-left: 10px;
        }

        .settings, .captions {
            position: absolute;
            bottom: 60px;
            right: 10px;
            background: rgba(0, 0, 0, 0.9);
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: none;
            min-width: 150px;
            z-index: 1000;
        }

        .settings.active, .captions.active {
            display: block;
            animation: slideIn 0.2s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .settings ul, .captions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .settings li, .captions li {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        .settings li:hover, .captions li:hover {
            background: #3498db;
        }

        .settings li.active, .captions li.active {
            background: #2563eb;
            font-weight: 600;
        }

        .settings > div {
            display: none;
        }

        .settings > div.active {
            display: block;
        }

        .download-btn {
            display: {{ $content->can_download == 1 ? 'inline-block' : 'none' }};
        }

        .speed-label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .speed-icon {
            font-size: 0.8rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Symbols+Outlined" rel="stylesheet">
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.content.index') }}">Content List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Content Details</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">View Content Details</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="mt-4 bg-gray-100 dark:bg-gray-900 min-h-screen">
                            <div class="container mx-auto px-4 py-6 max-w-7xl">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="relative w-full bg-black rounded-lg overflow-hidden media-container" style="min-height: 400px;">
                                            @if ($content->content)
                                                @php
                                                    $extension = strtolower($content->extension ?? '');
                                                    $contentType = strtolower($content->content_type ?? '');
                                                    $videoTypes = ['mp4', 'webm', 'ogg'];
                                                    $audioTypes = ['mp3', 'wav', 'ogg'];
                                                    $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
                                                    $assetPath = asset('storage/' . $content->content);
                                                    $resolutions = $content->resolutions ?? [
                                                        '240p' => $assetPath . '?res=240p',
                                                        '480p' => $assetPath . '?res=480p',
                                                        '720p' => $assetPath . '?res=720p',
                                                        '1080p' => $assetPath . '?res=1080p',
                                                    ];
                                                @endphp

                                                @if($contentType == 'video' || in_array($extension, $videoTypes))
                                                    <div class="video-container">
                                                        <div class="video_player">
                                                            <video class="main-video" {{ $content->can_download == 0 ? 'controlsList="nodownload nofullscreen noremoteplayback"' : '' }} preload="metadata" playsinline>
                                                                @foreach ($resolutions as $res => $path)
                                                                    <source src="{{ $path }}" size="{{ $res }}" type="video/{{ $extension }}">
                                                                @endforeach

                                                                <p class="media-fallback">Your browser does not support this video format. @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the video</a>.@else Please contact the administrator.@endif</p>
                                                            </video>

                                                            <div class="loader"></div>
                                                            
                                                            <p class="caption_text"></p>

                                                            <div class="progressAreaTime">0:00</div>

                                                            <div class="controls active">
                                                                <div class="progress-area">
                                                                    <canvas class="bufferedBar"></canvas>

                                                                    <div class="progress-bar">
                                                                        <span></span>
                                                                    </div>
                                                                </div>

                                                                <div class="controls-list">
                                                                    <div class="controls-left">
                                                                        <span class="icon">
                                                                            <i class="material-icons fast-rewind">replay_10</i>
                                                                        </span>
                                                                        <span class="icon">
                                                                            <i class="material-icons play_pause">play_arrow</i>
                                                                        </span>
                                                                        <span class="icon">
                                                                            <i class="material-icons fast-forward">forward_10</i>
                                                                        </span>
                                                                        <span class="icon">
                                                                            <i class="material-icons volume">volume_up</i>
                                                                            <input type="range" min="0" max="100" class="volume_range" value="80" />
                                                                        </span>
                                                                        <div class="timer">
                                                                            <span class="current">0:00</span> /
                                                                            <span class="duration">0:00</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="controls-right">
                                                                        @if ($content->can_download == 1)
                                                                            <span class="icon download-btn">
                                                                                <a href="{{ $assetPath }}" download><i class="material-icons">download</i></a>
                                                                            </span>
                                                                        @endif

                                                                        <span class="icon">
                                                                            <i class="material-icons auto-play"></i>
                                                                        </span>

                                                                        <span class="icon" style="display: none;">
                                                                            <i class="material-icons captionsBtn">closed_caption</i>
                                                                        </span>

                                                                        <span class="icon">
                                                                            <i class="material-icons settingsBtn">settings</i>
                                                                        </span>

                                                                        <span class="icon">
                                                                            <i class="material-icons picture_in_picutre">picture_in_picture_alt</i>
                                                                        </span>

                                                                        <span class="icon">
                                                                            <i class="material-icons fullscreen">fullscreen</i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="settings">
                                                                <div data-label="settingHome" class="active">
                                                                    <ul>
                                                                        <li data-label="speed">
                                                                            <span class="speed-label">Playback Speed (1x)</span>
                                                                            <span class="material-symbols-outlined icon speed-icon">speed</span>
                                                                        </li>

                                                                        {{-- <li data-label="quality">
                                                                            <span>Quality</span>
                                                                            <span class="material-symbols-outlined icon">arrow_forward_ios</span>
                                                                        </li> --}}
                                                                    </ul>
                                                                </div>

                                                                <div data-label="speed">
                                                                    <span>
                                                                        <i class="material-symbols-outlined icon back_arrow" data-label="settingHome">arrow_back</i>
                                                                        <span>Playback Speed</span>
                                                                    </span>
                                                                    <ul>
                                                                        <li data-speed="0.25">0.25x</li>
                                                                        <li data-speed="0.5">0.5x</li>
                                                                        <li data-speed="0.75">0.75x</li>
                                                                        <li data-speed="1" class="active">1x (Normal)</li>
                                                                        <li data-speed="1.25">1.25x</li>
                                                                        <li data-speed="1.5">1.5x</li>
                                                                        <li data-speed="1.75">1.75x</li>
                                                                        <li data-speed="2">2x</li>
                                                                        <li data-speed="2.5">2.5x</li>
                                                                        <li data-speed="3">3x</li>
                                                                    </ul>
                                                                </div>

                                                                <div data-label="quality">
                                                                    <span>
                                                                        <i class="material-symbols-outlined icon back_arrow" data-label="settingHome">arrow_back</i>
                                                                        <span>Playback Quality</span>
                                                                    </span>

                                                                    <ul>
                                                                        <li data-quality="auto" class="active">auto</li>
                                                                        
                                                                        @foreach ($resolutions as $res => $path)
                                                                            <li data-quality="{{ $res }}">{{ $res }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>

                                                            <div class="captions">
                                                                <div class="caption">
                                                                    <span>Select Subtitle</span>
                                                                    <ul></ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($contentType == 'audio' || in_array($extension, $audioTypes))
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                                        <audio {{ $content->can_download == 1 ? 'controls' : '' }} class="w-3/4" preload="metadata">
                                                            <source src="{{ $assetPath }}" type="audio/{{ $extension }}">
                                                            <p class="media-fallback">Your browser does not support this audio format. @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the audio</a>.@else Please contact the administrator.@endif</p>
                                                        </audio>
                                                    </div>
                                                @elseif($contentType == 'pdf' || $extension == 'pdf')
                                                    <div class="pdf-container">
                                                        <iframe src="{{ $assetPath }}#view=FitH&pagemode=none{{ $content->can_download == 1 ? '' : '&toolbar=0&navpanes=0' }}" class="w-full h-full" frameborder="0" title="PDF Viewer" scrolling="auto"></iframe>
                                                        <p class="pdf-fallback">If the PDF does not display, @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>download it here</a>.@else please contact the administrator.@endif</p>
                                                    </div>
                                                @elseif($contentType == 'image' || in_array($extension, $imageTypes))
                                                    <div class="image-container">
                                                        <img src="{{ $assetPath }}" alt="{{ $content->content_name }}" class="w-full h-full object-contain">
                                                        <p class="media-fallback">If the image does not display, @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>download it here</a>.@else please contact the administrator.@endif</p>
                                                    </div>
                                                @elseif($contentType == 'link')
                                                    <div class="link-container">
                                                        <a href="{{ $content->content }}" target="_blank" rel="noopener noreferrer">
                                                            <img src="https://media.istockphoto.com/id/1302329383/vector/two-chain-links-icon-attach-lock-symbol.jpg?s=612x612&w=0&k=20&c=c-dxZOv-E63rdJJ40lKPbO2wbb9y9jJpZ-s10ArX2l8=" alt="Link Thumbnail" class="w-full h-full object-contain">
                                                        </a>
                                                        <p class="media-fallback">If the link thumbnail does not display, @if ($content->can_download == 1)<a href="{{ $content->content }}" style="color: #129faf;" class="underline" target="_blank" rel="noopener noreferrer">visit the link here</a>.@else please contact the administrator.@endif</p>
                                                    </div>
                                                @else
                                                    <img src="{{ $content->thumbnail ? asset('storage/' . $content->thumbnail) : 'https://t4.ftcdn.net/jpg/05/17/53/57/360_F_517535712_q7f9QC9X6TQxWi6xYZZbMmw5cnLMr279.jpg' }}" alt="Content" class="w-full h-full object-cover">
                                                    <p class="media-fallback">No content available. @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the default content</a>.@else Please contact the administrator.@endif</p>
                                                @endif
                                            @else
                                                <img src="https://t4.ftcdn.net/jpg/05/17/53/57/360_F_517535712_q7f9QC9X6TQxWi6xYZZbMmw5cnLMr279.jpg" alt="Content" class="w-full h-full object-cover">
                                                <p class="media-fallback">No content available. @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the default content</a>.@else Please contact the administrator.@endif</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <h3 class="text-3xl font-bold text-gray-900 dark:mt-2 mb-4" style="padding: 10px">{{ $content->content_name ?? 'No Title' }}</h3>
                                        <div class="details-card p-6 mb-6">
                                            <h4 class="text-xl font-semibold text-gray-900 dark:mb-4">Description</h4>
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-0">{{ $content->description ?: 'No description available.' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 my-4">
                                        <div class="details-card p-6">
                                            <h4 class="text-xl font-semibold text-gray-900 dark:mb-6">Content Details</h4>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <span class="detail-label">Category</span>
                                                    <p class="detail-value">{{ $content->category->category_name ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <span class="detail-label">Content Type</span>
                                                    <p class="detail-value">{{ $content->content_type ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <span class="detail-label">Year</span>
                                                    <p class="detail-value">{{ $content->content_year ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <span class="detail-label">Created By</span>
                                                    <p class="detail-value">{{ $content->createdBy ? $content->createdBy->name_en : 'Unknown' }}</p>
                                                </div>
                                                @if (Auth::user()->user_type != 3)
                                                    <div class="col-12">
                                                        <span class="detail-label">Updated By</span>
                                                        <p class="detail-value">{{ $content->updatedBy ? $content->updatedBy->name_en : 'Unknown' }}</p>
                                                    </div>
                                                @endif
                                                @if ($content->status == 1)
                                                    <div class="col-12">
                                                        <span class="detail-label">Published On</span>
                                                        <p class="detail-value">{{ $content->published_at ? date('d M, Y', strtotime($content->published_at)) : 'Not Published' }}</p>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <span class="detail-label">Submitted On</span>
                                                        <p class="detail-value">{{ $content->created_at ? date('d M, Y', strtotime($content->created_at)) : 'Not Published' }}</p>
                                                    </div>
                                                    @if ($content->updated_by)
                                                        <div class="col-12">
                                                            <span class="detail-label">Updated On</span>
                                                            <p class="detail-value">{{ $content->updated_at ? date('d M, Y', strtotime($content->updated_at)) : 'Not Published' }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="col-12">
                                                    <span class="detail-label">Thumbnail</span>
                                                    <p class="detail-value">{{ $content->thumbnail ? 'Yes' : 'No' }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <span class="detail-label">Status</span>
                                                    <p class="detail-value mb-0">
                                                        @if ($content->status == 0)
                                                            <span class="badge bg-primary">Unpublished</span>
                                                        @elseif ($content->status == 1)
                                                            <span class="badge bg-success">Published</span>
                                                        @elseif ($content->status == 3)
                                                            <span class="badge bg-info">Archived</span>
                                                        @else
                                                            <span class="badge bg-danger">Undefined</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="offset-md-8 col-md-4 mt-4">
                                                    @if (Auth::id() == $content->created_by || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                                                        @if ($content->status == 0 && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                                                            @can('can_publish')
                                                                <button class="btn btn-success" type="button" onclick="publishContent('{{ Crypt::encryptString($content->id) }}')">Publish</button>
                                                            @endcan
                                                        @elseif ($content->status == 0 && Auth::user()->role_id == 3)
                                                            @if (($content->createdBy->userInfo->office_id ?? '') == (Auth::user()->userInfo->office_id ?? ''))
                                                                @can('can_publish')
                                                                    <button class="btn btn-success" type="button" onclick="publishContent('{{ Crypt::encryptString($content->id) }}')">Publish</button>
                                                                @endcan
                                                            @endif
                                                        @endif
                                                        @can('archive_content')
                                                            @if ($content->status == 3)
                                                                <button class="btn btn-info" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Unarchive</button>
                                                            @else
                                                                <button class="btn btn-info" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Archive</button>
                                                            @endif
                                                        @endcan
                                                        @can('delete_content')
                                                            <button class="btn btn-danger" type="button" onclick="deleteContent('{{ Crypt::encryptString($content->id) }}')">Delete</button>
                                                        @endcan
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Ensure jQuery is loaded
        try {
            if (typeof $ === 'undefined') {
                console.error('jQuery is not loaded.');
                alert('Error: jQuery is required for menu functionality. Please ensure it is included.');
            } else {
                $('[href*="{{ $menu_expand ?? '' }}"]').closest('.menu-dropdown').addClass('show');
                $('[href*="{{ $menu_expand ?? '' }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
                $('[href*="{{ $menu_expand ?? '' }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
                $('[href*="{{ $menu_expand ?? '' }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
            }
        } catch (e) {
            console.error('Error in jQuery menu expansion:', e);
        }

        document.addEventListener('DOMContentLoaded', function () {
            try {
                const video_players = document.querySelectorAll(".video_player");
                if (!video_players.length) {
                    console.warn('No video players found on the page.');
                    return;
                }

                video_players.forEach(video_player => {
                    const mainVideo = video_player.querySelector(".main-video"),
                        progressAreaTime = video_player.querySelector(".progressAreaTime"),
                        controls = video_player.querySelector(".controls"),
                        progressArea = video_player.querySelector(".progress-area"),
                        bufferedBar = video_player.querySelector(".bufferedBar"),
                        progress_Bar = video_player.querySelector(".progress-bar"),
                        fast_rewind = video_player.querySelector(".fast-rewind"),
                        play_pause = video_player.querySelector(".play_pause"),
                        fast_forward = video_player.querySelector(".fast-forward"),
                        volume = video_player.querySelector(".volume"),
                        volume_range = video_player.querySelector(".volume_range"),
                        current = video_player.querySelector(".current"),
                        totalDuration = video_player.querySelector(".duration"),
                        auto_play = video_player.querySelector(".auto-play"),
                        settingsBtn = video_player.querySelector(".settingsBtn"),
                        captionsBtn = video_player.querySelector(".captionsBtn"),
                        picture_in_picutre = video_player.querySelector(".picture_in_picutre"),
                        fullscreen = video_player.querySelector(".fullscreen"),
                        settings = video_player.querySelector(".settings"),
                        settingHome = video_player.querySelectorAll(".settings [data-label='settingHome'] > ul > li"),
                        captions = video_player.querySelector(".captions"),
                        caption_labels = video_player.querySelector(".captions ul"),
                        tracks = mainVideo ? mainVideo.querySelectorAll("track") : [],
                        loader = video_player.querySelector(".loader");

                    if (!mainVideo) {
                        console.error('Main video element not found.');
                        return;
                    }

                    let isPlaying = false;
                    let currentSource = mainVideo.querySelector("source[size]")?.getAttribute('src') || '';

                    // Force preload metadata
                    mainVideo.preload = "metadata";

                    // Update duration display
                    function updateDuration() {
                        if (!isNaN(mainVideo.duration) && isFinite(mainVideo.duration)) {
                            let videoDuration = mainVideo.duration;
                            let totalMin = Math.floor(videoDuration / 60);
                            let totalSec = Math.floor(videoDuration % 60);
                            totalSec = totalSec < 10 ? "0" + totalSec : totalSec;
                            totalDuration.innerHTML = `${totalMin}:${totalSec}`;
                        } else {
                            totalDuration.innerHTML = "0:00";
                        }
                    }

                    // Handle metadata loading with fallback
                    mainVideo.addEventListener("loadedmetadata", () => {
                        console.log('Metadata loaded, duration:', mainVideo.duration);
                        updateDuration();
                        loader.style.display = "none";
                        const fallback = video_player.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'none';
                    });

                    // Fallback for duration if metadata fails
                    mainVideo.addEventListener("loadeddata", () => {
                        if (isNaN(mainVideo.duration) || !isFinite(mainVideo.duration)) {
                            console.warn('Invalid duration, retrying metadata load');
                            mainVideo.load();
                        }
                    });

                    if (tracks.length) {
                        caption_labels.insertAdjacentHTML(
                            "afterbegin",
                            `<li data-track="OFF" class="active">OFF</li>`
                        );
                        for (let i = 0; i < tracks.length; i++) {
                            let trackLi = `<li data-track="${tracks[i].label}">${tracks[i].label}</li>`;
                            caption_labels.insertAdjacentHTML("beforeend", trackLi);
                        }
                    }
                    const caption = captions.querySelectorAll("ul li");

                    function playVideo() {
                        play_pause.innerHTML = "pause";
                        play_pause.title = "pause";
                        video_player.classList.add("paused");
                        isPlaying = true;
                        mainVideo.play().catch(e => {
                            console.error('Play error:', e);
                            if (typeof toastr !== 'undefined') {
                                toastr.error("Failed to play video.");
                            }
                        });
                    }

                    function pauseVideo() {
                        play_pause.innerHTML = "play_arrow";
                        play_pause.title = "play";
                        video_player.classList.remove("paused");
                        isPlaying = false;
                        mainVideo.pause();
                    }

                    play_pause.addEventListener("click", () => {
                        const isVideoPaused = video_player.classList.contains("paused");
                        isVideoPaused ? pauseVideo() : playVideo();
                    });

                    mainVideo.addEventListener("play", playVideo);
                    mainVideo.addEventListener("pause", pauseVideo);

                    fast_rewind.addEventListener("click", () => {
                        let newTime = Math.max(mainVideo.currentTime - 10, 0);
                        mainVideo.currentTime = newTime;
                        updateProgress();
                    });

                    fast_forward.addEventListener("click", () => {
                        let newTime = Math.min(mainVideo.currentTime + 10, mainVideo.duration || Infinity);
                        mainVideo.currentTime = newTime;
                        updateProgress();
                    });

                    function updateProgress() {
                        let currentVideoTime = mainVideo.currentTime;
                        let currentMin = Math.floor(currentVideoTime / 60);
                        let currentSec = Math.floor(currentVideoTime % 60);
                        currentSec = currentSec < 10 ? "0" + currentSec : currentSec;
                        current.innerHTML = `${currentMin}:${currentSec}`;

                        let videoDuration = mainVideo.duration || 0;
                        let progressWidth = videoDuration ? (currentVideoTime / videoDuration) * 100 : 0;
                        progress_Bar.style.width = `${progressWidth}%`;
                    }

                    mainVideo.addEventListener("timeupdate", updateProgress);

                    progressArea.addEventListener("pointerdown", (e) => {
                        progressArea.setPointerCapture(e.pointerId);
                        setTimelinePosition(e);
                        progressArea.addEventListener("pointermove", setTimelinePosition);
                        progressArea.addEventListener("pointerup", () => {
                            progressArea.removeEventListener("pointermove", setTimelinePosition);
                        }, { once: true });
                    });

                    function setTimelinePosition(e) {
                        let videoDuration = mainVideo.duration || 0;
                        let progressWidthval = progressArea.clientWidth;
                        let ClickOffsetX = e.offsetX;
                        let newTime = (ClickOffsetX / progressWidthval) * videoDuration;
                        mainVideo.currentTime = newTime;
                        updateProgress();
                    }

                    function drawProgress(canvas, buffered, duration) {
                        let context = canvas.getContext('2d', { antialias: false });
                        context.fillStyle = "#ffffffe6";
                        let height = canvas.height;
                        let width = canvas.width;
                        if (!height || !width) return;
                        context.clearRect(0, 0, width, height);
                        for (let i = 0; i < buffered.length; i++) {
                            let leadingEdge = buffered.start(i) / duration * width;
                            let trailingEdge = buffered.end(i) / duration * width;
                            context.fillRect(leadingEdge, 0, trailingEdge - leadingEdge, height);
                        }
                    }

                    mainVideo.addEventListener('progress', () => {
                        drawProgress(bufferedBar, mainVideo.buffered, mainVideo.duration || 0);
                    });

                    mainVideo.addEventListener('waiting', () => {
                        loader.style.display = "block";
                    });

                    mainVideo.addEventListener('canplay', () => {
                        loader.style.display = "none";
                        updateDuration();
                    });

                    function changeVolume() {
                        mainVideo.volume = volume_range.value / 100;
                        if (volume_range.value == 0) {
                            volume.innerHTML = "volume_off";
                        } else if (volume_range.value < 40) {
                            volume.innerHTML = "volume_down";
                        } else {
                            volume.innerHTML = "volume_up";
                        }
                    }

                    function muteVolume() {
                        if (volume_range.value == 0) {
                            volume_range.value = 80;
                            mainVideo.volume = 0.8;
                            volume.innerHTML = "volume_up";
                        } else {
                            volume_range.value = 0;
                            mainVideo.volume = 0;
                            volume.innerHTML = "volume_off";
                        }
                    }

                    volume_range.addEventListener("change", changeVolume);
                    volume.addEventListener("click", muteVolume);

                    progressArea.addEventListener("mousemove", (e) => {
                        let progressWidthval = progressArea.clientWidth;
                        let x = e.offsetX;
                        let videoDuration = mainVideo.duration || 0;
                        let progressTime = Math.floor((x / progressWidthval) * videoDuration);
                        let currentMin = Math.floor(progressTime / 60);
                        let currentSec = Math.floor(progressTime % 60);
                        currentSec = currentSec < 10 ? "0" + currentSec : currentSec;
                        progressAreaTime.style.setProperty("--x", `${x}px`);
                        progressAreaTime.style.display = "block";
                        if (x >= progressWidthval - 80) {
                            x = progressWidthval - 80;
                        } else if (x <= 75) {
                            x = 75;
                        }
                        progressAreaTime.innerHTML = `${currentMin}:${currentSec}`;
                    });

                    progressArea.addEventListener("mouseleave", () => {
                        progressAreaTime.style.display = "none";
                    });

                    auto_play.addEventListener("click", () => {
                        auto_play.classList.toggle("active");
                        auto_play.title = auto_play.classList.contains("active") ? "Autoplay is on" : "Autoplay is off";
                    });

                    mainVideo.addEventListener("ended", () => {
                        if (auto_play.classList.contains("active")) {
                            playVideo();
                        } else {
                            play_pause.innerHTML = "replay";
                            play_pause.title = "Replay";
                        }
                    });

                    picture_in_picutre.addEventListener("click", () => {
                        mainVideo.requestPictureInPicture().catch(e => {
                            console.error('PiP error:', e);
                            if (typeof toastr !== 'undefined') {
                                toastr.error("Picture-in-Picture is not supported or disabled.");
                            }
                        });
                    });

                    fullscreen.addEventListener("click", () => {
                        if (!video_player.classList.contains("openFullScreen")) {
                            video_player.classList.add("openFullScreen");
                            fullscreen.innerHTML = "fullscreen_exit";
                            video_player.requestFullscreen();
                        } else {
                            video_player.classList.remove("openFullScreen");
                            fullscreen.innerHTML = "fullscreen";
                            document.exitFullscreen();
                        }
                    });

                    settingsBtn.addEventListener("click", () => {
                        settings.classList.toggle("active");
                        settingsBtn.classList.toggle("active");
                        if (captionsBtn.classList.contains("active") || captions.classList.contains("active")) {
                            captions.classList.remove("active");
                            captionsBtn.classList.remove("active");
                        }
                    });

                    captionsBtn.addEventListener("click", () => {
                        captions.classList.toggle("active");
                        captionsBtn.classList.toggle("active");
                        if (settingsBtn.classList.contains("active") || settings.classList.contains("active")) {
                            settings.classList.remove("active");
                            settingsBtn.classList.remove("active");
                        }
                    });

                    const playback = video_player.querySelectorAll(".settings [data-label='speed'] li");
                    playback.forEach((event) => {
                        event.addEventListener("click", () => {
                            removeActiveClasses(playback);
                            event.classList.add("active");
                            let speed = parseFloat(event.getAttribute("data-speed"));
                            mainVideo.playbackRate = speed;
                            const speedLabel = video_player.querySelector(".settings [data-label='settingHome'] li[data-label='speed'] .speed-label");
                            if (speedLabel) {
                                speedLabel.textContent = `Playback Speed (${event.textContent})`;
                            }
                        });
                    });

                    caption.forEach((event) => {
                        event.addEventListener("click", () => {
                            removeActiveClasses(caption);
                            event.classList.add("active");
                            changeCaption(event);
                        });
                    });

                    function changeCaption(lable) {
                        let trackLable = lable.getAttribute("data-track");
                        for (let i = 0; i < tracks.length; i++) {
                            tracks[i].mode = "disabled";
                            if (tracks[i].label == trackLable) {
                                tracks[i].mode = "showing";
                            }
                        }
                    }

                    const settingDivs = video_player.querySelectorAll('.settings > div');
                    const settingBack = video_player.querySelectorAll('.settings > div .back_arrow');
                    const quality_ul = video_player.querySelector(".settings > [data-label='quality'] ul");
                    const qualities = mainVideo.querySelectorAll("source[size]");
                    const quality_li = quality_ul.querySelectorAll("li");

                    quality_li.forEach((event) => {
                        event.addEventListener('click', (e) => {
                            let quality = event.getAttribute('data-quality');
                            removeActiveClasses(quality_li);
                            event.classList.add("active");
                            qualities.forEach(source => {
                                if (source.getAttribute('size') === quality && source.getAttribute('src') !== currentSource) {
                                    let video_current_duration = mainVideo.currentTime;
                                    let wasPlaying = isPlaying;
                                    let currentPlaybackRate = mainVideo.playbackRate;
                                    currentSource = source.getAttribute('src');
                                    mainVideo.src = currentSource;
                                    mainVideo.currentTime = video_current_duration;
                                    mainVideo.playbackRate = currentPlaybackRate;
                                    mainVideo.load();
                                    mainVideo.addEventListener('loadedmetadata', () => {
                                        updateDuration();
                                        if (wasPlaying) playVideo();
                                    }, { once: true });
                                }
                            });
                        });
                    });

                    settingBack.forEach((event) => {
                        event.addEventListener('click', (e) => {
                            let setting_label = e.target.getAttribute('data-label');
                            console.log('Back clicked, returning to:', setting_label);
                            settingDivs.forEach(div => {
                                div.classList.toggle('active', div.getAttribute('data-label') === setting_label);
                            });
                        });
                    });

                    settingHome.forEach((event) => {
                        event.addEventListener('click', (e) => {
                            let setting_label = event.getAttribute('data-label');
                            console.log('Menu item clicked:', setting_label);
                            settingDivs.forEach(div => {
                                div.classList.toggle('active', div.getAttribute('data-label') === setting_label);
                            });
                        });
                    });

                    function removeActiveClasses(elements) {
                        elements.forEach((element) => {
                            element.classList.remove("active");
                        });
                    }

                    const caption_text = video_player.querySelector(".caption_text");
                    for (let i = 0; i < tracks.length; i++) {
                        tracks[i].addEventListener("cuechange", () => {
                            if (tracks[i].mode === "showing") {
                                if (tracks[i].activeCues[0]) {
                                    let span = `<span><mark>${tracks[i].activeCues[0].text}</mark></span>`;
                                    caption_text.innerHTML = span;
                                } else {
                                    caption_text.innerHTML = "";
                                }
                            }
                        });
                    }

                    @if ($content->can_download == 0)
                        mainVideo.addEventListener("contextmenu", (e) => {
                            e.preventDefault();
                        });
                    @endif

                    mainVideo.addEventListener("error", (e) => {
                        console.error('Video error:', e);
                        const fallback = video_player.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'block';
                        if (typeof toastr !== 'undefined') {
                            toastr.error("Failed to load video. Please check the file format or source path.");
                        }
                    });

                    let timer;
                    const hideControls = () => {
                        if (mainVideo.paused) return;
                        timer = setTimeout(() => {
                            if (settingsBtn.classList.contains("active") || captionsBtn.classList.contains("active")) {
                                controls.classList.add("active");
                            } else {
                                controls.classList.remove("active");
                                if (tracks.length) {
                                    caption_text.classList.add("active");
                                }
                            }
                        }, 3000);
                    };
                    hideControls();

                    video_player.addEventListener("mousemove", () => {
                        controls.classList.add("active");
                        if (tracks.length) {
                            caption_text.classList.remove("active");
                        }
                        clearTimeout(timer);
                        hideControls();
                    });

                    if (!tracks.length) {
                        if (caption_labels) caption_labels.remove();
                        if (captions) captions.remove();
                        if (captionsBtn) captionsBtn.parentNode.remove();
                    }
                });
            } catch (e) {
                console.error('Error in video player initialization:', e);
            }

            try {
                const audio = document.querySelector('audio');
                const pdfIframe = document.querySelector('.pdf-container iframe');
                const linkImg = document.querySelector('.link-container img');
                const image = document.querySelector('.image-container img');

                if (audio) {
                    audio.addEventListener('error', function (e) {
                        console.error('Audio error:', e);
                        const fallback = audio.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'block';
                        if (typeof toastr !== 'undefined') {
                            toastr.error("Failed to load audio. Please check the file format or source path.");
                        }
                    });

                    audio.addEventListener('loadeddata', function () {
                        const fallback = audio.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'none';
                    });
                }

                if (pdfIframe) {
                    const fallback = pdfIframe.parentElement.querySelector('.pdf-fallback');
                    pdfIframe.addEventListener('error', function (e) {
                        console.error('PDF iframe error:', e);
                        if (fallback) fallback.style.display = 'block';
                    });

                    pdfIframe.addEventListener('load', function () {
                        try {
                            if (pdfIframe.contentDocument && pdfIframe.contentDocument.contentType === 'application/pdf') {
                                console.log('PDF loaded successfully');
                                if (fallback) fallback.style.display = 'none';
                            } else {
                                console.error('PDF not loaded');
                                if (fallback) fallback.style.display = 'block';
                            }
                        } catch (e) {
                            console.error('PDF load check error:', e);
                            if (fallback) fallback.style.display = 'block';
                        }
                    });
                }

                if (linkImg) {
                    linkImg.addEventListener('error', function (e) {
                        console.error('Link thumbnail error:', e);
                        const fallback = linkImg.parentElement.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'block';
                    });

                    linkImg.addEventListener('load', function () {
                        const fallback = linkImg.parentElement.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'none';
                    });
                }

                if (image) {
                    image.addEventListener('error', function (e) {
                        console.error('Image error:', e);
                        const fallback = image.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'block';
                    });

                    image.addEventListener('load', function () {
                        const fallback = image.parentElement.querySelector('.media-fallback');
                        if (fallback) fallback.style.display = 'none';
                    });
                }
            } catch (e) {
                console.error('Error in media handling:', e);
            }
        });

        function deleteContent(id) {
            try {
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 is not loaded.');
                    alert('Error: Unable to delete content. Please ensure SweetAlert2 is included.');
                    return;
                }
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
                                    setTimeout(() => window.location.href = '{{ route('admin.content.index') }}', 1000);
                                }
                            },
                            error: function(xhr) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                                } else {
                                    alert('Error: ' + (xhr.responseJSON?.message || 'An error occurred.'));
                                }
                            }
                        });
                    }
                });
            } catch (e) {
                console.error('Error in deleteContent:', e);
                alert('Error: Unable to delete content.');
            }
        }

        function publishContent(id) {
            try {
                let submitBtn = $(document.activeElement);
                let btnText = submitBtn.text();
                submitBtn.prop('disabled', true);
                submitBtn.html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ${btnText}ing...`);
                $.ajax({
                    url: "{{ route('admin.content.publish', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });
                            }
                            setTimeout(() => window.location.reload(), 1000);
                        }
                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);
                    },
                    error: function(xhr) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                        } else {
                            alert('Error: ' + (xhr.responseJSON?.message || 'An error occurred.'));
                        }
                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);
                    }
                });
            } catch (e) {
                console.error('Error in publishContent:', e);
                alert('Error: Unable to publish content.');
            }
        }

        function archiveContent(id) {
            try {
                let submitBtn = $(document.activeElement);
                let btnText = submitBtn.text();
                submitBtn.prop('disabled', true);
                submitBtn.html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ${btnText}ing...`);
                $.ajax({
                    url: "{{ route('admin.content.archive', ':id') }}".replace(':id', id),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                });
                            }
                            setTimeout(() => window.location.reload(), 1000);
                        }
                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);
                    },
                    error: function(xhr) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');
                        } else {
                            alert('Error: ' + (xhr.responseJSON?.message || 'An error occurred.'));
                        }
                        submitBtn.prop('disabled', false);
                        submitBtn.html(btnText);
                    }
                });
            } catch (e) {
                console.error('Error in archiveContent:', e);
                alert('Error: Unable to archive content.');
            }
        }
    </script>
@endpush