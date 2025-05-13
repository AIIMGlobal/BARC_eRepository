@extends('backend.layouts.app')

@section('title', 'View Content | '.($global_setting->title ?? ""))

@push('css')
    <style>
        video::-webkit-media-controls,
        audio::-webkit-media-controls {
            display: block !important;
            opacity: 1 !important;
            z-index: 10 !important;
        }
        video,
        audio {
            max-width: 100%;
            max-height: 100%;
        }
        .relative {
            overflow: visible !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-content bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <div class="relative w-full bg-black rounded-lg overflow-hidden mb-6 shadow-lg" style="aspect-ratio: 16/9;">
                @if($content->content)
                    @php
                        $extension = strtolower($content->extension);
                        $contentType = strtolower($content->content_type);
                    @endphp

                    @if($contentType == 'video' || in_array($extension, ['mp4', 'webm', 'ogg']))
                        <video class="w-full h-full object-contain" controls controlsList="nodownload" preload="metadata">
                            <source src="{{ asset('storage/' . $content->content) }}" type="video/{{ $extension == 'mp4' ? 'mp4' : ($extension == 'webm' ? 'webm' : 'ogg') }}">
                            <p class="text-white text-center">Your browser does not support this video format.</p>
                        </video>
                    @elseif($contentType == 'audio' || in_array($extension, ['mp3', 'wav', 'ogg']))
                        <div class="w-full h-full flex items-center justify-center bg-gray-800">
                            <audio controls class="w-3/4" preload="metadata">
                                <source src="{{ asset('storage/' . $content->content) }}" type="audio/{{ $extension == 'mp3' ? 'mpeg' : ($extension == 'wav' ? 'wav' : 'ogg') }}">
                                <p class="text-white text-center">Your browser does not support this audio format.</p>
                            </audio>
                        </div>
                    @elseif($contentType == 'pdf' || $extension == 'pdf')
                        <iframe src="{{ asset('storage/' . $content->content) }}#toolbar=0" class="w-full h-full" frameborder="0"></iframe>
                    @elseif($contentType == 'image' || in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $content->content) }}" alt="{{ $content->content_name }}" class="w-full h-full object-contain">
                    @else
                        <img src="{{ $content->thumbnail ? asset('storage/' . $content->thumbnail) : asset('images/dummy-content.jpg') }}" alt="Content" class="w-full h-full object-cover">
                    @endif
                @else
                    <img src="{{ asset('images/dummy-content.jpg') }}" alt="Content" class="w-full h-full object-cover">
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $content->content_name }}</h1>

                    <div class="flex items-center space-x-4 mb-4">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Uploaded by: {{ $content->createdBy ? $content->createdBy->name_en : 'Unknown' }}</span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Published: {{ $content->published_at ? $content->published_at->format('M d, Y') : 'Not Published' }}</span>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h2>
                        <p class="text-gray-700 dark:text-gray-300">{{ $content->meta_description ?: 'No description available.' }}</p>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Content Details</h2>

                        <ul class="space-y-2 text-gray-700 dark:https://x.com/using-x/x-premiumtext-gray-300">
                            <li><strong>Category:</strong> {{ $content->category->category_name ?? 'N/A' }}</li>
                            <li><strong>Content Type:</strong> {{ $content->content_type }}</li>
                            <li><strong>Year:</strong> {{ $content->content_year }}</li>
                            <li><strong>Extension:</strong> {{ $content->extension ?: 'N/A' }}</li>
                            <li><strong>Status:</strong> {{ $content->status ? 'Published' : 'Draft' }}</li>
                        </ul>
                    </div>

                    <a href="{{ route('admin.content.indexMyContent') }}" class="block mt-4 text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Back to Content List</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.querySelector('video');
            const audio = document.querySelector('audio');

            if (video) {
                video.addEventListener('error', function () {
                    alert('Failed to load video. Please check the file or format. Error: ' . video.error);
                });
            }

            if (audio) {
                audio.addEventListener('error', function () {
                    alert('Failed to load audio. Please check the file or format. Error: ' . audio.error);
                });
            }
        });
    </script>
@endpush