@php
    $activeVariants = $product->variants->where('is_active', true);
    $hasOptions = !empty($product->options_config) && is_array($product->options_config);
    $hasCustomizations = !empty($product->customizations_config) && is_array($product->customizations_config);
    $hasVariants = $activeVariants->isNotEmpty() || $hasOptions || $hasCustomizations;
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
                    data-product-name="{{ $product->name }}"
                    data-product-slug="{{ $product->slug }}"
                    data-product-price="{{ $product->price }}"
                    data-product-stock="{{ (int)$product->stock_quantity }}"
                    data-product-image="{{ asset($product->cover_image_url) }}"
                    data-product-category="{{ $product->category ? $product->category->name : '' }}"
                    data-has-variants="{{ $hasVariants ? 'true' : 'false' }}"
                    data-options-config="{{ json_encode($product->options_config ?? []) }}"
                    data-customizations-config="{{ json_encode($product->customizations_config ?? []) }}"
                    data-variants="{{ $hasVariants ? json_encode($activeVariants->values()) : '[]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Añadir a la Bolsa</span>
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

        <!-- Mobile Always-Visible Action Button -->
        <div class="card-product-mobile-action">
            <button type="button" 
                    class="btn btn-primary btn-sm btn-block" 
                    data-action="add-to-cart" 
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->name }}"
                    data-product-slug="{{ $product->slug }}"
                    data-product-price="{{ $product->price }}"
                    data-product-stock="{{ (int)$product->stock_quantity }}"
                    data-product-image="{{ asset($product->cover_image_url) }}"
                    data-product-category="{{ $product->category ? $product->category->name : '' }}"
                    data-has-variants="{{ $hasVariants ? 'true' : 'false' }}"
                    data-options-config="{{ json_encode($product->options_config ?? []) }}"
                    data-customizations-config="{{ json_encode($product->customizations_config ?? []) }}"
                    data-variants="{{ $hasVariants ? json_encode($activeVariants->values()) : '[]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Añadir a la Bolsa</span>
            </button>
        </div>
    </div>
</article>
