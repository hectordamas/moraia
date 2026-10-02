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
        <!-- Gallery Column (Interactive Carousel & Thumbnails) -->
        <div class="product-gallery">
            @php
                $galleryImages = $product->images->isNotEmpty() 
                    ? $product->images 
                    : collect([(object)['image_path' => $product->cover_image_url]]);
            @endphp

            <div class="product-gallery-slider" id="product-gallery-slider" data-total-slides="{{ $galleryImages->count() }}">
                @if($product->badge)
                    <span class="badge badge-rose card-product-badge">{{ $product->badge }}</span>
                @endif

                <div class="product-gallery-viewport" id="product-gallery-viewport">
                    <div class="product-gallery-track" id="product-gallery-track">
                        @foreach($galleryImages as $idx => $img)
                            <div class="product-gallery-slide {{ $idx === 0 ? 'active' : '' }}" data-slide-index="{{ $idx }}">
                                <img src="{{ asset($img->image_path) }}" alt="{{ $product->name }} - Foto {{ $idx + 1 }}" class="product-gallery-img">
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($galleryImages->count() > 1)
                    <!-- Navigation Arrows -->
                    <button type="button" class="gallery-nav-arrow gallery-nav-prev" id="gallery-prev-btn" aria-label="Foto anterior">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button type="button" class="gallery-nav-arrow gallery-nav-next" id="gallery-next-btn" aria-label="Foto siguiente">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <!-- Dot Indicators -->
                    <div class="gallery-dots-bar" id="gallery-dots">
                        @foreach($galleryImages as $idx => $img)
                            <button type="button" class="gallery-dot-btn {{ $idx === 0 ? 'active' : '' }}" data-slide-to="{{ $idx }}" aria-label="Ir a foto {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($galleryImages->count() > 1)
                <div class="product-thumbnails" id="product-thumbnails">
                    @foreach($galleryImages as $idx => $img)
                        <button type="button" 
                                class="product-thumb-btn {{ $idx === 0 ? 'active' : '' }}" 
                                data-slide-to="{{ $idx }}" 
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
                <span class="product-info-price" id="display-product-price">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                    <span class="product-info-price-old">${{ number_format($product->compare_at_price, 2) }}</span>
                    <span class="badge badge-rose" style="font-size: 0.75rem;">Ahorras ${{ number_format($product->compare_at_price - $product->price, 2) }}</span>
                @endif
                <span id="product-display-sku" style="font-size: 0.75rem; color: var(--color-text-muted); margin-left: auto; font-family: monospace;">SKU: {{ $product->sku ?: 'MOR' }}</span>
            </div>

            <!-- Dynamic Stock Status Badge -->
            <div class="product-info-stock" id="product-stock-container" style="margin-bottom: var(--space-4);">
                @if($product->stock_quantity > 5)
                    <span class="product-stock-badge in-stock" id="product-stock-badge">
                        <span class="product-info-stock-dot" style="background-color: #2E7D32;"></span>
                        <span id="product-stock-text">Disponible en stock ({{ $product->stock_quantity }} unidades)</span>
                    </span>
                @elseif($product->stock_quantity > 0)
                    <span class="product-stock-badge low-stock" id="product-stock-badge">
                        <span class="product-info-stock-dot" style="background-color: #E65100;"></span>
                        <span id="product-stock-text">¡Últimas {{ $product->stock_quantity }} unidades disponibles!</span>
                    </span>
                @else
                    <span class="product-stock-badge out-stock" id="product-stock-badge">
                        <span class="product-info-stock-dot" style="background-color: #C62828;"></span>
                        <span id="product-stock-text">Agotado temporalmente</span>
                    </span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <div class="product-info-desc">
                    {{ $product->short_description }}
                </div>
            @endif

            <!-- 1. Dynamic Attribute Combination Selectors (Talla, Color, Tela, etc.) -->
            @if(!empty($product->options_config) && is_array($product->options_config))
                <div id="product-attributes-container" style="margin-bottom: var(--space-5);">
                    @foreach($product->options_config as $aIdx => $attr)
                        @if(!empty($attr['name']) && !empty($attr['values']))
                            <div class="variant-group" data-attribute-name="{{ $attr['name'] }}">
                                <div class="variant-label">
                                    <span>{{ $attr['name'] }}:</span>
                                    <strong class="selected-attribute-val" id="selected-attr-label-{{ $aIdx }}">{{ $attr['values'][0] }}</strong>
                                </div>
                                <div class="variant-options">
                                    @foreach($attr['values'] as $vIdx => $val)
                                        <button type="button" 
                                                class="variant-pill {{ $vIdx === 0 ? 'active' : '' }}" 
                                                data-attr-name="{{ $attr['name'] }}"
                                                data-attr-val="{{ $val }}"
                                                data-aidx="{{ $aIdx }}">
                                            {{ $val }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @elseif($product->variants->isNotEmpty())
                <!-- Fallback for standard single-axis variants -->
                @php
                    $groupedVariants = $product->variants->groupBy('variant_type');
                @endphp

                <div id="product-legacy-variants-container" style="margin-bottom: var(--space-5);">
                    @foreach($groupedVariants as $type => $vars)
                        <div class="variant-group">
                            <div class="variant-label">{{ ucfirst($type) }}:</div>
                            <div class="variant-options">
                                @foreach($vars as $vIdx => $v)
                                    <button type="button" 
                                            class="variant-pill {{ $vIdx === 0 ? 'active' : '' }} {{ $v->stock_quantity <= 0 ? 'out-of-stock' : '' }}" 
                                            data-variant-id="{{ $v->id }}"
                                            data-sku="{{ $v->sku ?: $product->sku }}"
                                            data-stock="{{ $v->stock_quantity }}"
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
                </div>
            @endif

            <!-- 2. Customizations & Add-ons (Single & Multiple Choice) -->
            @if(!empty($product->customizations_config) && is_array($product->customizations_config))
                <div class="product-customizations-section">
                    @foreach($product->customizations_config as $cIdx => $group)
                        <div class="product-custom-group">
                            <div class="variant-label" style="margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ $group['title'] }}:</span>
                                <span class="custom-selection-badge">
                                    {{ ($group['selectionType'] ?? 'single') === 'multiple' ? 'Selección múltiple' : 'Selección única' }}
                                </span>
                            </div>

                            <div class="product-custom-cards-grid">
                                @if(($group['selectionType'] ?? 'single') === 'single')
                                    <!-- Single Choice (Radio) -->
                                    @foreach($group['options'] ?? [] as $oIdx => $opt)
                                        <label class="product-addon-card {{ $oIdx === 0 ? 'selected' : '' }}">
                                            <input type="radio" 
                                                   name="custom_group_{{ $cIdx }}" 
                                                   value="{{ $opt['label'] }}" 
                                                   data-price="{{ $opt['price'] ?? 0 }}"
                                                   class="custom-addon-input"
                                                   {{ $oIdx === 0 ? 'checked' : '' }}>
                                            <div class="addon-card-indicator radio-indicator"></div>
                                            <div class="addon-card-content">
                                                <span class="addon-card-title">{{ $opt['label'] }}</span>
                                                @if(!empty($opt['price']) && (float)$opt['price'] > 0)
                                                    <span class="addon-card-price">+${{ number_format((float)$opt['price'], 2) }}</span>
                                                @else
                                                    <span class="addon-card-included">Incluido</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <!-- Multiple Choice (Checkboxes) -->
                                    @foreach($group['options'] ?? [] as $oIdx => $opt)
                                        <label class="product-addon-card">
                                            <input type="checkbox" 
                                                   name="custom_group_{{ $cIdx }}[]" 
                                                   value="{{ $opt['label'] }}" 
                                                   data-price="{{ $opt['price'] ?? 0 }}"
                                                   class="custom-addon-input">
                                            <div class="addon-card-indicator checkbox-indicator">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </div>
                                            <div class="addon-card-content">
                                                <span class="addon-card-title">{{ $opt['label'] }}</span>
                                                @if(!empty($opt['price']) && (float)$opt['price'] > 0)
                                                    <span class="addon-card-price">+${{ number_format((float)$opt['price'], 2) }}</span>
                                                @else
                                                    <span class="addon-card-included">Opcional</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <input type="hidden" id="product-selected-variant-id" value="{{ $product->variants->first()?->id }}">

            <!-- Add to Cart Actions -->
            <div class="product-add-actions">
                <div class="product-qty-select">
                    <button type="button" class="qty-btn" id="product-qty-minus" aria-label="Disminuir">-</button>
                    <input type="text" id="product-qty-input" class="qty-input" value="1" readonly>
                    <button type="button" class="qty-btn" id="product-qty-plus" aria-label="Aumentar">+</button>
                </div>

                <button type="button" 
                        class="btn btn-primary btn-lg product-add-btn" 
                        id="btn-add-to-cart-main"
                        data-action="add-to-cart" 
                        data-product-id="{{ $product->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span id="btn-add-to-cart-text">Añadir a mi Bolsa</span>
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

    <!-- Related Products Carousel -->
    @if($relatedProducts->isNotEmpty())
        <div class="related-products-section">
            <div class="related-products-header">
                <div>
                    <span class="section-tag">Combina & Descubre</span>
                    <h2 class="section-title">Productos Relacionados</h2>
                </div>
                @if($relatedProducts->count() > 1)
                    <div class="related-carousel-nav">
                        <button type="button" class="related-nav-btn" id="related-prev-btn" aria-label="Productos anteriores">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" class="related-nav-btn" id="related-next-btn" aria-label="Productos siguientes">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <div class="related-carousel-viewport" id="related-carousel-viewport">
                <div class="related-carousel-track" id="related-carousel-track">
                    @foreach($relatedProducts as $relProduct)
                        <div class="related-carousel-item">
                            @include('partials.product-card', ['product' => $relProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productVariants = @json($product->variants ?? []);
    const optionsConfig = @json($product->options_config ?? []);
    const basePrice = {{ (float)$product->price }};
    const baseStock = {{ (int)$product->stock_quantity }};
    const baseSku = "{{ $product->sku ?: 'MOR' }}";

    const displayPriceEl = document.getElementById('display-product-price');
    const displaySkuEl = document.getElementById('product-display-sku');
    const stockBadgeEl = document.getElementById('product-stock-badge');
    const stockTextEl = document.getElementById('product-stock-text');
    const selectedVariantInput = document.getElementById('product-selected-variant-id');
    const addBtn = document.getElementById('btn-add-to-cart-main');
    const addBtnText = document.getElementById('btn-add-to-cart-text');

    let currentVariantPriceMod = 0;

    function getSelectedOptions() {
        const selected = {};
        document.querySelectorAll('#product-attributes-container .variant-group').forEach(group => {
            const attrName = group.getAttribute('data-attribute-name');
            const activePill = group.querySelector('.variant-pill.active');
            if (attrName && activePill) {
                selected[attrName] = activePill.getAttribute('data-attr-val');
            }
        });
        return selected;
    }

    function getCustomizationsTotal() {
        let total = 0;
        document.querySelectorAll('.custom-addon-input:checked').forEach(inp => {
            const p = parseFloat(inp.getAttribute('data-price') || inp.dataset.price || 0);
            total += isNaN(p) ? 0 : p;
        });
        return total;
    }

    function updatePrice() {
        const customTotal = getCustomizationsTotal();
        const finalPrice = basePrice + currentVariantPriceMod + customTotal;
        if (displayPriceEl) {
            displayPriceEl.textContent = '$' + finalPrice.toFixed(2);
        }
    }

    function updateStockBadge(stock, isActive) {
        if (!stockBadgeEl || !stockTextEl) return;

        stockBadgeEl.className = 'product-stock-badge';
        const qtyInputEl = document.getElementById('product-qty-input');
        if (qtyInputEl) {
            qtyInputEl.setAttribute('data-max-stock', stock);
            let currentQ = parseInt(qtyInputEl.value, 10) || 1;
            if (stock <= 0) {
                qtyInputEl.value = '1';
            } else if (currentQ > stock) {
                qtyInputEl.value = stock;
            }
        }

        if (!isActive || stock <= 0) {
            stockBadgeEl.classList.add('out-stock');
            stockTextEl.textContent = 'Agotado en esta combinación';
            if (addBtn) {
                addBtn.disabled = true;
                addBtn.style.opacity = '0.6';
                addBtn.style.cursor = 'not-allowed';
            }
            if (addBtnText) addBtnText.textContent = 'Agotado';
        } else if (stock <= 5) {
            stockBadgeEl.classList.add('low-stock');
            stockTextEl.textContent = `¡Últimas ${stock} unidades disponibles!`;
            if (addBtn) {
                addBtn.disabled = false;
                addBtn.style.opacity = '1';
                addBtn.style.cursor = 'pointer';
            }
            if (addBtnText) addBtnText.textContent = 'Añadir a mi Bolsa';
        } else {
            stockBadgeEl.classList.add('in-stock');
            stockTextEl.textContent = `Disponible en stock (${stock} unidades)`;
            if (addBtn) {
                addBtn.disabled = false;
                addBtn.style.opacity = '1';
                addBtn.style.cursor = 'pointer';
            }
            if (addBtnText) addBtnText.textContent = 'Añadir a mi Bolsa';
        }
    }

    function syncCombinations() {
        if (productVariants.length === 0) {
            updateStockBadge(baseStock, baseStock > 0);
            updatePrice();
            return;
        }

        if (optionsConfig.length === 0) {
            updatePrice();
            return;
        }

        const currentSelected = getSelectedOptions();

        // 1. Exact match by options dictionary
        let matched = productVariants.find(v => {
            if (!v.options || typeof v.options !== 'object') return false;
            return Object.entries(currentSelected).every(([k, val]) => v.options[k] === val);
        });

        // 2. Fallback match by compound name
        if (!matched) {
            const comboName = Object.values(currentSelected).join(' / ');
            matched = productVariants.find(v => v.name === comboName);
        }

        // 3. Fallback match by individual value or name
        if (!matched) {
            const selectedVals = Object.values(currentSelected);
            matched = productVariants.find(v => {
                if (v.value && selectedVals.includes(v.value)) return true;
                if (v.name && selectedVals.includes(v.name)) return true;
                if (v.name && selectedVals.some(sv => v.name.toLowerCase().includes(sv.toLowerCase()))) return true;
                return false;
            });
        }

        // 4. Default fallback to first active variant
        if (!matched && productVariants.length > 0) {
            matched = productVariants.find(v => v.is_active && v.stock_quantity > 0) || productVariants[0];
        }

        if (matched) {
            if (selectedVariantInput) selectedVariantInput.value = matched.id;
            if (displaySkuEl) displaySkuEl.textContent = 'SKU: ' + (matched.sku || baseSku);
            currentVariantPriceMod = parseFloat(matched.price_modifier || 0);
            updateStockBadge(matched.stock_quantity, matched.is_active);
        } else {
            if (selectedVariantInput) selectedVariantInput.value = '';
            currentVariantPriceMod = 0;
            updateStockBadge(baseStock, baseStock > 0);
        }

        updatePrice();
    }

    // Bind Attributes Pills
    const attrPills = document.querySelectorAll('#product-attributes-container .variant-pill');
    attrPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const group = this.closest('.variant-group');
            if (group) {
                group.querySelectorAll('.variant-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const aidx = this.getAttribute('data-aidx');
                const labelEl = document.getElementById(`selected-attr-label-${aidx}`);
                if (labelEl) labelEl.textContent = this.getAttribute('data-attr-val');
            }
            syncCombinations();
        });
    });

    // Bind Legacy Variant Pills
    const legacyPills = document.querySelectorAll('#product-legacy-variants-container .variant-pill');
    legacyPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const group = this.closest('.variant-group');
            if (group) {
                group.querySelectorAll('.variant-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            }
            const vId = this.getAttribute('data-variant-id');
            const sku = this.getAttribute('data-sku');
            const stock = parseInt(this.getAttribute('data-stock') || '0', 10);
            const priceMod = parseFloat(this.getAttribute('data-price-mod') || '0');

            if (selectedVariantInput) selectedVariantInput.value = vId;
            if (displaySkuEl) displaySkuEl.textContent = 'SKU: ' + sku;
            currentVariantPriceMod = priceMod;
            updateStockBadge(stock, true);
            updatePrice();
        });
    });

    // Bind Customizations Inputs & Card Selection Sync
    function syncCustomAddonCards() {
        document.querySelectorAll('.product-addon-card').forEach(card => {
            const inp = card.querySelector('.custom-addon-input');
            if (inp && inp.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    document.querySelectorAll('.custom-addon-input').forEach(inp => {
        inp.addEventListener('change', function() {
            syncCustomAddonCards();
            updatePrice();
        });
    });

    syncCustomAddonCards();

    function getSelectedCustomizations() {
        const list = [];
        // Capture attribute options (Talla, Copa, etc.)
        document.querySelectorAll('#product-attributes-container .variant-group').forEach(group => {
            const attrName = group.getAttribute('data-attribute-name');
            const activePill = group.querySelector('.variant-pill.active');
            if (attrName && activePill) {
                list.push({
                    group: attrName,
                    label: activePill.getAttribute('data-attr-val') || activePill.textContent.trim(),
                    price: 0
                });
            }
        });
        // Capture custom add-ons
        document.querySelectorAll('.custom-addon-input:checked').forEach(inp => {
            const label = inp.value;
            const price = parseFloat(inp.getAttribute('data-price') || inp.dataset.price || 0);
            const groupTitle = inp.closest('.product-custom-group')?.querySelector('.variant-label span')?.textContent?.replace(':', '')?.trim() || 'Personalización';
            list.push({
                group: groupTitle,
                label: label,
                price: isNaN(price) ? 0 : price
            });
        });
        return list;
    }

    // Direct click listener for Main Add to Cart Button
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const productId = this.getAttribute('data-product-id');
            const variantId = selectedVariantInput && selectedVariantInput.value ? selectedVariantInput.value : null;
            const qtyInput = document.getElementById('product-qty-input');
            const quantity = qtyInput ? parseInt(qtyInput.value, 10) : 1;
            const customizations = getSelectedCustomizations();

            if (typeof window.addToCartAjax === 'function') {
                window.addToCartAjax(productId, variantId, quantity, this, customizations);
            } else if (typeof addToCartAjax === 'function') {
                addToCartAjax(productId, variantId, quantity, this, customizations);
            }
        });
    }

    // Initial sync
    if (optionsConfig.length > 0 || productVariants.length > 0) {
        syncCombinations();
    } else {
        updatePrice();
    }
});
</script>
@endpush
