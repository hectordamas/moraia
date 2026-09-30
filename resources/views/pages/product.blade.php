@extends('layouts.app')

@section('title', $product->seo_title ?? ($product->name . ' | MORAIA'))
@section('meta_description', $product->seo_description ?? $product->short_description)
@section('og_image', asset($product->cover_image_url))

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": [
    "{{ asset($product->cover_image_url) }}"
  ],
  "description": "{{ $product->short_description }}",
  "sku": "{{ $product->sku }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "USD",
    "price": "{{ $product->price }}",
    "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "seller": {
      "@type": "Organization",
      "name": "MORAIA"
    }
  }
}
</script>
@endpush

@section('content')
<div class="container section-padding">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
        <a href="{{ route('home') }}">Inicio</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('shop') }}">Tienda</a>
        @if($product->category)
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a>
        @endif
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail Layout -->
    <div class="product-detail-grid">
        <!-- Gallery Column -->
        <div class="product-gallery">
            <div class="product-main-img-wrap">
                @if($product->badge)
                    <span class="badge badge-rose card-product-badge">{{ $product->badge }}</span>
                @endif
                <img src="{{ asset($product->cover_image_url) }}" alt="{{ $product->name }}" class="product-main-img" id="main-product-image">
            </div>

            @if($product->images->count() > 1)
                <div class="product-thumbnails">
                    @foreach($product->images as $idx => $img)
                        <button type="button" 
                                class="product-thumb-btn {{ $idx === 0 ? 'active' : '' }}" 
                                data-full-img="{{ asset($img->image_path) }}" 
                                aria-label="Ver foto {{ $idx + 1 }}">
                            <img src="{{ asset($img->image_path) }}" alt="{{ $product->name }}" class="product-thumb-img">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Information Column -->
        <div class="product-info">
            <input type="hidden" id="base-product-price" value="{{ $product->price }}">

            @if($product->category)
                <span class="product-info-cat">{{ $product->category->name }}</span>
            @endif

            <h1 class="product-info-title">{{ $product->name }}</h1>

            <!-- Price & Compare -->
            <div class="product-info-price-wrap">
                <span class="product-info-price">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                    <span class="product-info-price-old">${{ number_format($product->compare_at_price, 2) }}</span>
                    <span class="badge badge-rose" style="font-size: 0.75rem;">Ahorras ${{ number_format($product->compare_at_price - $product->price, 2) }}</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="product-info-stock">
                <span class="product-info-stock-dot"></span>
                <span>Disponible en stock para entrega inmediata</span>
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <div class="product-info-desc">
                    {{ $product->short_description }}
                </div>
            @endif

            <!-- Variants Selector -->
            @if($product->variants->isNotEmpty())
                @php
                    $groupedVariants = $product->variants->groupBy('variant_type');
                @endphp

                @foreach($groupedVariants as $type => $vars)
                    <div class="variant-group">
                        <div class="variant-label">{{ ucfirst($type) }}:</div>
                        <div class="variant-options">
                            @foreach($vars as $vIdx => $v)
                                <button type="button" 
                                        class="variant-pill {{ $vIdx === 0 ? 'active' : '' }}" 
                                        data-variant-id="{{ $v->id }}"
                                        data-price-mod="{{ $v->price_modifier }}">
                                    {{ $v->name }}
                                    @if($v->price_modifier > 0)
                                        (+${{ number_format($v->price_modifier, 2) }})
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Add to Cart Actions -->
            <div class="product-add-actions">
                <div class="product-qty-select">
                    <button type="button" class="qty-btn" id="product-qty-minus" aria-label="Disminuir">-</button>
                    <input type="text" id="product-qty-input" class="qty-input" value="1" readonly>
                    <button type="button" class="qty-btn" id="product-qty-plus" aria-label="Aumentar">+</button>
                </div>

                <button type="button" 
                        class="btn btn-primary btn-lg product-add-btn" 
                        data-action="add-to-cart" 
                        data-product-id="{{ $product->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    Añadir a mi Bolsa
                </button>
            </div>

            <!-- Direct WhatsApp Consultation CTA -->
            <div style="margin-bottom: var(--space-8);">
                <a href="https://wa.me/584120206548?text={{ rawurlencode("Hola Moraia, quisiera consultar la disponibilidad y detalles sobre el producto: {$product->name} (SKU: {$product->sku}) - " . url()->current()) }}" 
                   target="_blank" 
                   rel="noopener" 
                   class="btn btn-outline btn-block btn-sm" 
                   style="border-color: #25D366; color: #128C7E;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Consultar por WhatsApp
                </a>
            </div>

            <!-- Trust Features -->
            <div class="product-trust-box">
                <div class="trust-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V3.75A1.125 1.125 0 0 0 13.125 2.625h-7.5A1.125 1.125 0 0 0 4.5 3.75v10.5" />
                    </svg>
                    <span>Delivery en Caracas en 24h & Envíos Nacionales a toda Venezuela.</span>
                </div>
                <div class="trust-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <span>Presentación en empaque de lujo con tarjeta de regalo incluida.</span>
                </div>
                <div class="trust-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>Acabados premium y atención 100% personalizada.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Long Description & Details Tabs -->
    @if($product->description)
        <div style="margin-top: var(--space-16); padding-top: var(--space-10); border-top: 1px solid var(--color-border);">
            <div style="max-width: 800px; margin: 0 auto;">
                <h2 style="font-family: var(--font-display); font-size: 1.75rem; margin-bottom: var(--space-6); text-align: center;">
                    Detalles & Experiencia
                </h2>
                <div style="color: var(--color-text-light); line-height: 1.8; font-size: 1.05rem; white-space: pre-line;">
                    {{ $product->description }}
                </div>
            </div>
        </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div style="margin-top: var(--space-20);">
            <div class="section-header">
                <span class="section-tag">Combina & Descubre</span>
                <h2 class="section-title">Productos Relacionados</h2>
            </div>
            <div class="product-grid">
                @foreach($relatedProducts as $relProduct)
                    @include('partials.product-card', ['product' => $relProduct])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
