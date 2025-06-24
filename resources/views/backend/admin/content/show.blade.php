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

        .details-card:hover {
            /* transform: translateY(-5px); */
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
                                                    $extension = strtolower($content->extension);
                                                    $contentType = strtolower($content->content_type);
                                                    $videoTypes = ['mp4', 'webm', 'ogg'];
                                                    $audioTypes = ['mp3', 'wav', 'ogg'];
                                                    $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
                                                    $assetPath = asset('storage/' . $content->content);
                                                @endphp

                                                @if($contentType == 'video' || in_array($extension, $videoTypes))
                                                    <div class="video-container">
                                                        <video class="w-full h-full object-contain" {{ $content->can_download == 1 ? 'controls' : 'controls controlsList="nodownload nofullscreen noremoteplayback"' }} disablePictureInPicture preload="metadata" playsinline>
                                                            <source src="{{ $assetPath }}" type="video/{{ $extension }}">

                                                            <p class="media-fallback">Your browser does not support this video format. @if ($content->can_download == 1)<a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the video</a>.@else Please contact the administrator.@endif</p>
                                                        </video>
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
                                        <h3 class="text-3xl font-bold text-gray-900 dark:mt-2 mb-4" style="padding: 10px">{{ $content->content_name }}</h3>

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
                                                    <p class="detail-value">{{ $content->content_type }}</p>
                                                </div>

                                                <div class="col-12">
                                                    <span class="detail-label">Year</span>
                                                    <p class="detail-value">{{ $content->content_year }}</p>
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
                                                        <span class="detail-label">Published At</span>
                                                        <p class="detail-value">{{ $content->published_at ? date('d M, Y', strtotime($content->published_at)) : 'Not Published' }}</p>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <span class="detail-label">Submitted At</span>
                                                        <p class="detail-value">{{ $content->created_at ? date('d M, Y', strtotime($content->created_at)) : 'Not Published' }}</p>
                                                    </div>

                                                    @if ($content->updated_by)
                                                        <div class="col-12">
                                                            <span class="detail-label">Updated At</span>
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
                                                                <button class="btn btn-primary" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Unarchive</button>
                                                            @else
                                                                <button class="btn btn-primary" type="button" onclick="archiveContent('{{ Crypt::encryptString($content->id) }}')">Archive</button>
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
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');

        document.addEventListener('DOMContentLoaded', function () {
            const video = document.querySelector('video');
            const audio = document.querySelector('audio');
            const pdfIframe = document.querySelector('.pdf-container iframe');
            const linkImg = document.querySelector('.link-container img');

            if (video) {
                @if ($content->can_download == 0)
                    video.addEventListener('contextmenu', function (e) {
                        e.preventDefault();
                    });
                    
                    video.controlsList = 'nodownload nofullscreen noremoteplayback';

                    video.addEventListener('loadedmetadata', function () {
                        video.controlsList = 'nodownload nofullscreen noremoteplayback';
                    });
                @endif

                video.addEventListener('error', function (e) {
                    console.error('Video error:', e);

                    const fallback = video.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'block';
                    }

                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 1500;
                    toastr.error("Failed to load video. Please check the file format or source path.");
                });

                video.addEventListener('loadeddata', function () {
                    console.log('Video loaded successfully');

                    const fallback = video.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                });
            }

            if (audio) {
                audio.addEventListener('error', function (e) {
                    console.error('Audio error:', e);

                    const fallback = audio.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'block';
                    }

                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 1500;
                    toastr.error("Failed to load audio. Please check the file format or source path.");
                });

                audio.addEventListener('loadeddata', function () {
                    const fallback = audio.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                });
            }

            if (pdfIframe) {
                const fallback = pdfIframe.parentElement.querySelector('.pdf-fallback');

                pdfIframe.addEventListener('error', function (e) {
                    console.error('PDF iframe error:', e);

                    if (fallback) {
                        fallback.style.display = 'block';
                    }
                });

                pdfIframe.addEventListener('load', function () {
                    try {
                        if (pdfIframe.contentDocument && pdfIframe.contentDocument.contentType === 'application/pdf') {
                            console.log('PDF loaded successfully');

                            if (fallback) {
                                fallback.style.display = 'none';
                            }
                        } else {
                            console.error('PDF not loaded');

                            if (fallback) {
                                fallback.style.display = 'block';
                            }
                        }
                    } catch (e) {
                        console.error('PDF load check error:', e);

                        if (fallback) {
                            fallback.style.display = 'block';
                        }
                    }
                });
            }

            if (linkImg) {
                linkImg.addEventListener('error', function (e) {
                    console.error('Link thumbnail error:', e);

                    const fallback = linkImg.parentElement.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'block';
                    }
                });

                linkImg.addEventListener('load', function () {
                    const fallback = linkImg.parentElement.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                });
            }

            const image = document.querySelector('.image-container img');
            
            if (image) {
                image.addEventListener('error', function (e) {
                    console.error('Image error:', e);

                    const fallback = image.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'block';
                    }
                });

                image.addEventListener('load', function () {
                    const fallback = image.parentElement.querySelector('.media-fallback');

                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
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

                                setTimeout(() => window.location.href = '{{ route('admin.content.index') }}', 1000);
                            }
                        }
                    });
                }
            });
        }

        function publishContent(id) {
            let submitBtn = $(document.activeElement);
            let btnText = submitBtn.text();

            submitBtn.prop('disabled', true);
            submitBtn.html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ${btnText}ing...`);

            $.ajax({
                url: "{{ route('admin.content.publish', ':id') }}".replace(':id', id),
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

                    submitBtn.prop('disabled', false);
                    submitBtn.html(btnText);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');

                    submitBtn.prop('disabled', false);
                    submitBtn.html(btnText);
                }
            });
        }

        function archiveContent(id) {
            let submitBtn = $(document.activeElement);
            let btnText = submitBtn.text();

            submitBtn.prop('disabled', true);
            submitBtn.html(`<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ${btnText}ing...`);

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

                    submitBtn.prop('disabled', false);
                    submitBtn.html(btnText);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred.', 'Error');

                    submitBtn.prop('disabled', false);
                    submitBtn.html(btnText);
                }
            });
        }
    </script>
@endpush