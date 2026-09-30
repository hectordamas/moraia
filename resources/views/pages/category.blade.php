@extends('layouts.app')

@section('title', $category->seo_title ?? ($category->name . ' | MORAIA'))
@section('meta_description', $category->seo_description ?? $category->description)

@section('content')
<!-- Full-Width Category Hero -->
<section class="category-hero-section">
    <!-- Animated Vector Background Elements -->
    <div class="category-hero-vectors" aria-hidden="true">
        <!-- Botanical Vector Top Right -->
        <svg class="cat-vector cat-vector-top-right cat-anim-float-slow" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M40,100 C70,40 140,50 180,20 C160,80 180,140 140,180 C90,160 50,150 40,100 Z" fill="rgba(200, 116, 126, 0.09)"/>
            <path d="M70,80 C90,40 140,50 160,30 C150,70 160,110 130,140 C100,120 75,115 70,80 Z" fill="rgba(200, 116, 126, 0.07)"/>
            <circle cx="150" cy="50" r="4" fill="rgba(200, 116, 126, 0.28)" class="cat-anim-pulse"/>
        </svg>

        <!-- Curved Luxury Waves Bottom Left -->
        <svg class="cat-vector cat-vector-bottom-left cat-anim-float-reverse" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="200" r="140" stroke="rgba(200, 116, 126, 0.12)" stroke-width="1.5" stroke-dasharray="6 6"/>
            <circle cx="40" cy="200" r="100" stroke="rgba(200, 116, 126, 0.14)" stroke-width="1.5"/>
            <circle cx="40" cy="200" r="60" stroke="rgba(200, 116, 126, 0.18)" stroke-width="1"/>
            <path d="M10,180 Q60,120 120,150 T200,100" stroke="rgba(200, 116, 126, 0.16)" stroke-width="2" fill="none"/>
        </svg>

        <!-- Delicate Sparkles -->
        <div class="cat-sparkle cat-sparkle-1 cat-anim-sparkle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.45)"/>
            </svg>
        </div>
        <div class="cat-sparkle cat-sparkle-2 cat-anim-sparkle" style="animation-delay: 1.5s;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.35)"/>
            </svg>
        </div>
        <div class="cat-sparkle cat-sparkle-3 cat-anim-sparkle" style="animation-delay: 2.8s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.3)"/>
            </svg>
        </div>
    </div>

    <div class="container category-hero-container">
        <!-- Breadcrumbs inside Hero -->
        <nav class="breadcrumbs category-hero-breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('shop') }}">Tienda</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{ $category->name }}</span>
        </nav>

        <div class="category-hero-content">
            <div class="category-hero-badge">
                <span class="category-badge-dot"></span>
                Colección Exclusiva Moraia
            </div>

            <h1 class="category-hero-title">{{ $category->name }}</h1>

            @if($category->description)
                <p class="category-hero-description" style="margin-bottom: 0;">
                    {{ $category->description }}
                </p>
            @endif
        </div>
    </div>
</section>

<div class="container section-padding" style="padding-top: 0;">

    <!-- Products (Full Width Grid) -->
    <div>
        @if($products->isEmpty())
            <div class="text-center" style="padding: 60px 20px; background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                <div style="font-size: 3rem; margin-bottom: 15px;">🌸</div>
                <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 8px;">Próximamente nuevos ingresos</h3>
                <p style="color: var(--color-text-muted); margin-bottom: 20px;">
                    Estamos preparando nuevos productos para esta categoría.
                </p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Ver Otros Productos</a>
            </div>
        @else
            <div class="product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products->hasPages())
                <div style="margin-top: var(--space-10); display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

