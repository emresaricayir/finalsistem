@extends('layouts.app')

@section('title', $page->title)

@section('styles')
<style>
/* Sayfa İçeriği Resim Efektleri */
.prose img {
    border-radius: 12px;
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    margin: 20px 0;
    max-width: 100%;
    height: auto;
    display: block;
}

.prose img:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 40px -8px rgba(0, 0, 0, 0.25);
}

/* Resim Hizalama Sınıfları */
.prose img.align-left {
    float: left;
    margin-right: 25px;
    margin-bottom: 15px;
    margin-top: 0;
}

.prose img.align-right {
    float: right;
    margin-left: 25px;
    margin-bottom: 15px;
    margin-top: 0;
}

.prose img.align-center {
    display: block;
    margin: 25px auto;
}

/* Resim Boyut Sınıfları */
.prose img.small {
    max-width: 200px;
}

.prose img.medium {
    max-width: 400px;
}

.prose img.large {
    max-width: 600px;
}

/* Özel Efekt Sınıfları */
.prose img.shadow-soft {
    box-shadow: 0 4px 15px -4px rgba(0, 0, 0, 0.1);
}

.prose img.shadow-medium {
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
}

.prose img.shadow-strong {
    box-shadow: 0 12px 35px -8px rgba(0, 0, 0, 0.25);
}

.prose img.border-thin {
    border: 2px solid #e5e7eb;
}

.prose img.border-medium {
    border: 4px solid #d1d5db;
}

.prose img.border-thick {
    border: 6px solid #9ca3af;
}

.prose img.rounded-none {
    border-radius: 0;
}

.prose img.rounded-small {
    border-radius: 6px;
}

.prose img.rounded-medium {
    border-radius: 12px;
}

.prose img.rounded-large {
    border-radius: 20px;
}

.prose img.rounded-full {
    border-radius: 50%;
}

/* Hover Efektleri */
.prose img.hover-lift:hover {
    transform: translateY(-6px);
}

.prose img.hover-scale:hover {
    transform: scale(1.05);
}

.prose img.hover-rotate:hover {
    transform: rotate(2deg);
}

/* Özel Renk Efektleri */
.prose img.sepia {
    filter: sepia(100%);
}

.prose img.grayscale {
    filter: grayscale(100%);
}

.prose img.blur-soft {
    filter: blur(1px);
}

.prose img.brightness {
    filter: brightness(1.2);
}

.prose img.contrast {
    filter: contrast(1.2);
}

/* Özel Animasyon Efektleri */
.prose img.fade-in {
    animation: fadeInUp 0.8s ease-out;
}

.prose img.slide-in-left {
    animation: slideInLeft 0.8s ease-out;
}

.prose img.slide-in-right {
    animation: slideInRight 0.8s ease-out;
}

.prose img.zoom-in {
    animation: zoomIn 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Resim Galeri Efektleri */
.prose .image-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.prose .image-gallery img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.prose .image-gallery img:hover {
    transform: scale(1.05);
    z-index: 10;
    position: relative;
}

/* Resim Caption Efektleri */
.prose .image-caption {
    text-align: center;
    font-style: italic;
    color: #6b7280;
    font-size: 0.9em;
    margin-top: 8px;
    padding: 8px 16px;
    background: #f9fafb;
    border-radius: 6px;
    border-left: 4px solid #0d9488;
}

/* Responsive Resim Efektleri */
@media (max-width: 768px) {
    .prose img.align-left,
    .prose img.align-right {
        float: none;
        display: block;
        margin: 20px auto;
    }

    .prose img.small,
    .prose img.medium,
    .prose img.large {
        max-width: 100%;
    }
}
</style>
@endsection

@section('meta')
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
@endsection

@section('content')
@include('partials.header-menu-wrapper')

<main class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-teal-600">
                            <i class="fas fa-home mr-2"></i>
                            Ana Sayfa
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $page->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>Oluşturulma: {{ $page->created_at->format('d.m.Y') }}</span>
                    @if($page->updated_at != $page->created_at)
                        <span class="mx-2">•</span>
                        <i class="fas fa-edit mr-2"></i>
                        <span>Güncelleme: {{ $page->updated_at->format('d.m.Y') }}</span>
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <div class="prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>

            <!-- Page Footer -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-link mr-2"></i>
                        <span>URL: <code class="bg-gray-100 px-2 py-1 rounded">{{ request()->url() }}</code></span>
                    </div>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Ana Sayfaya Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

