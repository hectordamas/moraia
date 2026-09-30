@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' | ' : '') . 'Tienda & Catálogo Oficial | MORAIA')
@section('meta_description', $activeCategory ? $activeCategory->seo_description : 'Explora la colección completa de Moraia: pijamas, lencería, belleza y cajas de regalo en Venezuela.')

@section('content')
<!-- Full-Width Page Hero (Shop / Catalog) -->
<section class="page-hero-section">
    <div class="page-hero-vectors" aria-hidden="true">
        <svg class="cat-vector page-vector-right cat-anim-float-slow" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M40,100 C70,40 140,50 180,20 C160,80 180,140 140,180 C90,160 50,150 40,100 Z" fill="rgba(200, 116, 126, 0.08)"/>
            <circle cx="150" cy="50" r="4" fill="rgba(200, 116, 126, 0.25)" class="cat-anim-pulse"/>
        </svg>
        <div class="page-sparkle page-sparkle-1 cat-anim-sparkle">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.4)"/></svg>
        </div>
        <div class="page-sparkle page-sparkle-2 cat-anim-sparkle" style="animation-delay: 1.5s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2L14.2 9.8L22 12L14.2 14.2L12 22L9.8 14.2L2 12L9.8 9.8L12 2Z" fill="rgba(200, 116, 126, 0.3)"/></svg>
        </div>
    </div>

    <div class="container page-hero-container">
        <nav class="breadcrumbs page-hero-breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('shop') }}">Tienda</a>
            @if($activeCategory)
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">{{ $activeCategory->name }}</span>
            @elseif($search)
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">Búsqueda: "{{ $search }}"</span>
            @elseif($line === 'intimo')
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">Moraia Íntimo</span>
            @endif
        </nav>

        <div class="page-hero-badge">
            <span class="page-badge-dot"></span>
            Catálogo Oficial Moraia
        </div>

        <h1 class="page-hero-title">
            @if($activeCategory)
                {{ $activeCategory->name }}
            @elseif($search)
                Resultados para "{{ $search }}"
            @elseif($line === 'intimo')
                Moraia Íntimo (18+)
            @else
                Colección Completa
            @endif
        </h1>

        <p class="page-hero-desc">
            @if($activeCategory)
                {{ $activeCategory->description }}
            @elseif($line === 'intimo')
                Lencería fina y bienestar íntimo seleccionados con total discreción y sofisticación.
            @else
                Piezas pensadas para consentirte y regalar experiencias inolvidables.
            @endif
        </p>
    </div>
</section>

<div class="container section-padding" style="padding-top: var(--space-4);">

    <!-- Product Listing & Toolbar -->
    <div>
        <!-- Toolbar -->
        <div class="shop-toolbar">
            <div class="shop-results-count">
                Mostrando <strong>{{ $products->count() }}</strong> de <strong>{{ $products->total() }}</strong> productos
            </div>

            <form method="GET" action="{{ url()->current() }}" class="shop-sort-wrap" id="sort-form">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                @if(request('line')) <input type="hidden" name="line" value="{{ request('line') }}"> @endif
                
                <label for="sort-select" class="shop-sort-label">Ordenar:</label>
                <select name="sort" id="sort-select" class="shop-sort-select" onchange="document.getElementById('sort-form').submit()">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Más recientes</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Destacados</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                </select>
            </form>
        </div>

        <!-- Grid -->
        @if($products->isEmpty())
            <div class="text-center" style="padding: 60px 20px; background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
                <div style="font-size: 3rem; margin-bottom: 15px;">🔍</div>
                <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 8px;">No se encontraron productos</h3>
                <p style="color: var(--color-text-muted); margin-bottom: 20px;">
                    Prueba con otros términos de búsqueda o revisa todas nuestras categorías.
                </p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Ver Todos los Productos</a>
            </div>
        @else
            <div class="product-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div style="margin-top: var(--space-10); display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

