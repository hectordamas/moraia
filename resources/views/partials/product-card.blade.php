@php
    $firstVariant = $product->variants->first();
@endphp

<article class="card-product">
    <div class="card-product-img-wrap">
        @if($product->badge)
            <span class="badge badge-rose card-product-badge">{{ $product->badge }}</span>
        @endif
        
        @if($product->discount_percentage)
            <span class="card-product-discount">-{{ $product->discount_percentage }}%</span>
        @endif

        <a href="{{ route('product', $product->slug) }}" aria-label="{{ $product->name }}">
            <img src="{{ asset($product->cover_image_url) }}" alt="{{ $product->name }}" class="card-product-img" loading="lazy">
        </a>

        <div class="card-product-actions">
            <button type="button" 
                    class="btn btn-primary btn-sm btn-block" 
                    data-action="add-to-cart" 
                    data-product-id="{{ $product->id }}"
                    data-variant-id="{{ $firstVariant?->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Añadir a la Bolsa
            </button>
        </div>
    </div>

    <div class="card-product-body">
        @if($product->category)
            <a href="{{ route('category', $product->category->slug) }}" class="card-product-category">
                {{ $product->category->name }}
            </a>
        @endif

        <h3 class="card-product-title">
            <a href="{{ route('product', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        <div class="card-product-price-wrap">
            <span class="card-product-price">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <span class="card-product-price-old">${{ number_format($product->compare_at_price, 2) }}</span>
            @endif
        </div>

        <!-- Mobile Always-Visible Add to Cart Button -->
        <div class="card-product-mobile-action">
            <button type="button" 
                    class="btn btn-primary btn-sm btn-block" 
                    data-action="add-to-cart" 
                    data-product-id="{{ $product->id }}"
                    data-variant-id="{{ $firstVariant?->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Añadir a la Bolsa</span>
            </button>
        </div>
    </div>
</article>
