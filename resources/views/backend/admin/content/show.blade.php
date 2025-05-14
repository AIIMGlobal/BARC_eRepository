@extends('backend.layouts.app')

@section('title', 'View Content Details | '.($global_setting->title ?? ""))

@push('css')
    <style>
        .video-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        video {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            outline: none;
        }

        video::-webkit-media-controls,
        video::-webkit-media-controls-panel,
        video::-webkit-media-controls-enclosure {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        video[controls] {
            display: block !important;
        }

        .pdf-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            flex-grow: 1;
        }

        .pdf-fallback {
            text-align: center;
            padding: 1rem;
            margin: 0;
            color: #fff;
            background: rgba(0, 0, 0, 0.7);
            display: block; /* Always visible for now, toggle via JS if needed */
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
                                        <div class="relative w-full bg-black rounded-lg overflow-hidden mb-6 shadow-lg" style="aspect-ratio: 16/9; min-height: 400px;">
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
                                                        <video class="w-full h-full object-contain" controls controlsList="nodownload" disablePictureInPicture preload="metadata" playsinline>
                                                            <source src="{{ $assetPath }}" type="video/{{ $extension == 'mp4' ? 'mp4' : ($extension == 'webm' ? 'webm' : 'ogg') }}">

                                                            <p class="text-center p-4">Your browser does not support this video format. <a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>Download the video</a>.</p>
                                                        </video>
                                                    </div>
                                                @elseif($contentType == 'audio' || in_array($extension, $audioTypes))
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                                        <audio controls class="w-3/4" preload="metadata">
                                                            <source src="{{ $assetPath }}" type="audio/{{ $extension == 'mp3' ? 'mpeg' : ($extension == 'wav' ? 'wav' : 'ogg') }}">

                                                            <p class="text-center">Your browser does not support this audio format.</p>
                                                        </audio>
                                                    </div>
                                                @elseif($contentType == 'pdf' || $extension == 'pdf')
                                                    <div class="pdf-container">
                                                        <iframe src="{{ $assetPath }}#toolbar=0&view=FitH" class="w-full h-full" frameborder="0" title="PDF Viewer"></iframe>
                                                        <p class="pdf-fallback">If the PDF does not display, <a href="{{ $assetPath }}" style="color: #129faf;" class="underline" download>download it here</a>.</p>
                                                    </div>
                                                @elseif($contentType == 'image' || in_array($extension, $imageTypes))
                                                    <img src="{{ $assetPath }}" alt="{{ $content->content_name }}" class="w-full h-full object-contain">
                                                @else
                                                    <img src="{{ $content->thumbnail ? asset('storage/' . $content->thumbnail) : asset('images/dummy-content.jpg') }}" alt="Content" class="w-full h-full object-cover">
                                                @endif
                                            @else
                                                <img src="{{ asset('images/dummy-content.jpg') }}" alt="Content" class="w-full h-full object-cover">
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

                                                <div class="col-12">
                                                    <span class="detail-label">Published At</span>
                                                    <p class="detail-value">{{ $content->published_at ? date('d M, Y', strtotime($content->published_at)) : 'Not Published' }}</p>
                                                </div>

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
        // $('[href*="{{ $menu_expand }}"]').addClass('active');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').addClass('show');
        $('[href*="{{ $menu_expand }}"]').closest('.menu-dropdown').parent().find('.nav-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-link').attr('aria-expanded', 'true');
        $('[href*="{{ $menu_expand }}"]').closest('.first-dropdown').find('.menu-dropdown:first').addClass('show');
        
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.querySelector('video');
            const audio = document.querySelector('audio');
            const pdfIframe = document.querySelector('.pdf-container iframe');

            if (video) {
                video.controls = true;

                video.addEventListener('error', function (e) {
                    console.error('Video error:', e);
                    alert('Failed to load video. Please check the file format or source path.');
                });

                video.addEventListener('loadeddata', function () {
                    console.log('Video loaded successfully');
                    video.controls = true;
                });

                video.addEventListener('mouseover', function () {
                    video.controls = true;
                });
                video.addEventListener('click', function () {
                    video.controls = true;
                });
            }

            if (audio) {
                audio.addEventListener('error', function (e) {
                    console.error('Audio error:', e);
                    alert('Failed to load audio. Please check the file format or source path.');
                });
            }

            if (pdfIframe) {
                const fallback = pdfIframe.parentElement.querySelector('.pdf-fallback');

                // Show fallback on iframe error
                pdfIframe.addEventListener('error', function (e) {
                    console.error('PDF iframe error:', e);
                    if (fallback) {
                        fallback.style.display = 'block';
                    }
                });

                // Check if PDF loaded successfully
                pdfIframe.addEventListener('load', function () {
                    try {
                        if (pdfIframe.contentDocument && pdfIframe.contentDocument.contentType === 'application/pdf') {
                            console.log('PDF loaded successfully');
                            if (fallback) {
                                fallback.style.display = 'none'; // Hide fallback if PDF loads
                            }
                        } else {
                            console.error('PDF not loaded');
                            if (fallback) {
                                fallback.style.display = 'block'; // Show fallback if not a PDF
                            }
                        }
                    } catch (e) {
                        console.error('PDF load check error:', e);
                        if (fallback) {
                            fallback.style.display = 'block'; // Show fallback on error
                        }
                    }
                });
            }
        });
    </script>
@endpush