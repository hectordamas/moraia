@extends('layouts.app')

@section('title', 'Bolsa de Compras | MORAIA')
@section('meta_description', 'Revisa los productos seleccionados en tu bolsa de compras Moraia.')

@section('content')
<div class="container section-padding">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
        <a href="{{ route('home') }}">Inicio</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Bolsa de Compras</span>
    </nav>

    <div style="margin-bottom: var(--space-8);">
        <h1 style="font-size: clamp(2rem, 4vw, 2.75rem); margin-bottom: var(--space-2);">Tu Bolsa de Compras</h1>
        <p style="color: var(--color-text-muted);">Verifica tus artículos antes de proceder a la orden.</p>
    </div>

    @if(empty($cart))
        <div class="text-center" style="padding: 80px 20px; background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            <div style="font-size: 3.5rem; margin-bottom: 15px;">🛍️</div>
            <h2 style="font-family: var(--font-display); font-size: 1.75rem; margin-bottom: 10px;">Tu bolsa está vacía</h2>
            <p style="color: var(--color-text-muted); margin-bottom: 25px;">
                Explora nuestras colecciones para consentirte o armar un regalo memorable.
            </p>
            <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Explorar Tienda</a>
        </div>
    @else
        <div class="checkout-grid">
            <!-- Items Table -->
            <div class="cart-page-items">
                @include('partials.cart-page-items', ['cart' => $cart])
            </div>

            <!-- Summary Box -->
            <div class="checkout-card checkout-summary">
                <h3 class="checkout-card-title">Resumen de Compra</h3>

                <div class="summary-row">
                    <span>Artículos ({{ $itemCount }})</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>

                <div class="summary-row">
                    <span>Envío</span>
                    <span style="font-size: var(--text-xs); color: var(--color-primary-dark);">Calculado en checkout</span>
                </div>

                <div class="summary-row total">
                    <span>Subtotal Estimado</span>
                    <span class="cart-page-subtotal" style="color: var(--color-primary-dark);">${{ number_format($subtotal, 2) }}</span>
                </div>

                <div style="margin-top: var(--space-6);">
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg btn-block" style="margin-bottom: var(--space-3);">
                        Proceder a la Orden &rarr;
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-outline btn-block btn-sm">
                        Continuar Comprando
                    </a>
                </div>

                <div style="margin-top: var(--space-6); padding-top: var(--space-4); border-top: 1px solid var(--color-border-light); font-size: 0.75rem; color: var(--color-text-muted); line-height: 1.5;">
                    ✨ <strong>Importante:</strong> No requerimos pago online con tarjeta de crédito. Al completar tu orden podrás coordinar el pago directamente por WhatsApp.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
