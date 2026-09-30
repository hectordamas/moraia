@php
    $cart = $cart ?? session()->get('cart', []);
@endphp

@if(empty($cart))
    <div class="text-center" style="padding: 40px 10px;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🛍️</div>
        <h4 style="font-family: var(--font-display); font-size: 1.3rem; margin-bottom: 8px;">Tu bolsa está vacía</h4>
        <p style="color: var(--color-text-muted); font-size: var(--text-sm); margin-bottom: 20px;">
            Descubre nuestra colección de pijamas, lencería y cajas de regalo.
        </p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-sm" onclick="closeMiniCart()">Explorar Catálogo</a>
    </div>
@else
    @foreach($cart as $item)
        <div class="cart-item">
            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="cart-item-img">
            <div class="cart-item-info">
                <a href="{{ route('product', $item['slug']) }}" class="cart-item-title">{{ $item['name'] }}</a>
                @if(!empty($item['variant_name']))
                    <span class="cart-item-variant">{{ $item['variant_name'] }}</span>
                @endif
                <div class="cart-item-price">${{ number_format($item['price'], 2) }}</div>
                
                <div class="cart-item-bottom">
                    <div class="qty-counter">
                        <button type="button" class="qty-btn" data-action="cart-qty" data-cart-key="{{ $item['key'] }}" data-delta="-1" aria-label="Disminuir">-</button>
                        <span class="qty-input">{{ $item['quantity'] }}</span>
                        <button type="button" class="qty-btn" data-action="cart-qty" data-cart-key="{{ $item['key'] }}" data-delta="1" aria-label="Aumentar">+</button>
                    </div>
                    <button type="button" class="cart-item-remove" data-action="cart-remove" data-cart-key="{{ $item['key'] }}" aria-label="Eliminar producto">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endforeach
@endif
