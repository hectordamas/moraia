@php
    $cart = $cart ?? session()->get('cart', []);
@endphp

@if(empty($cart))
    <div class="text-center" style="padding: 60px 20px;">
        <div style="font-size: 3.5rem; margin-bottom: 15px;">🛍️</div>
        <h3 style="font-family: var(--font-display); font-size: 1.75rem; margin-bottom: 10px;">Tu bolsa de compras está vacía</h3>
        <p style="color: var(--color-text-muted); margin-bottom: 25px;">
            Aún no has agregado ningún producto a tu pedido.
        </p>
        <a href="{{ route('shop') }}" class="btn btn-primary">Ir a la Tienda</a>
    </div>
@else
    <div class="table-responsive">
        <table class="admin-table" style="background-color: #FFFFFF; border-radius: var(--radius-sm);">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-4">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 60px; height: 60px; aspect-ratio: 1 / 1; object-fit: contain; background-color: var(--color-surface-soft); border-radius: var(--radius-xs); border: 1px solid var(--color-border-light);">
                                <div>
                                    <a href="{{ route('product', $item['slug']) }}" style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 600; color: var(--color-text);">
                                        {{ $item['name'] }}
                                    </a>
                                    @if(!empty($item['variant_name']))
                                        <div style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px;">
                                            {{ $item['variant_name'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>${{ number_format($item['price'], 2) }}</strong>
                        </td>
                        <td style="text-align: center;">
                            <div class="qty-counter" style="margin: 0 auto;">
                                <button type="button" class="qty-btn" data-action="cart-qty" data-cart-key="{{ $item['key'] }}" data-delta="-1">-</button>
                                <span class="qty-input">{{ $item['quantity'] }}</span>
                                <button type="button" class="qty-btn" data-action="cart-qty" data-cart-key="{{ $item['key'] }}" data-delta="1">+</button>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--color-primary-dark); font-size: 1.05rem;">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </strong>
                        </td>
                        <td>
                            <button type="button" class="btn-icon-table" data-action="cart-remove" data-cart-key="{{ $item['key'] }}" title="Eliminar de la bolsa" style="color: var(--color-error);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
