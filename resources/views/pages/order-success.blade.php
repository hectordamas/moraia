@extends('layouts.app')

@section('title', 'Orden Confirmada #' . $order->order_code . ' | MORAIA')
@section('meta_description', '¡Tu orden ha sido registrada con éxito! Confirma tu pedido por WhatsApp para coordinar la entrega.')

@section('content')
<div class="container section-padding">
    <div class="order-success-box">
        <!-- Success Icon -->
        <div class="success-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <span class="section-tag">¡Pedido Registrado con Amor!</span>
        <h1 style="font-size: clamp(2rem, 4vw, 2.5rem); margin-bottom: var(--space-2);">
            ¡Muchas gracias, {{ $order->customer_name }}!
        </h1>

        <div class="order-code-badge">
            Orden #{{ $order->order_code }}
        </div>

        <p style="color: var(--color-text-light); font-size: 1.05rem; line-height: 1.7; margin-bottom: var(--space-6); max-width: 540px; margin-left: auto; margin-right: auto;">
            Tu orden está registrada en nuestro sistema. Para coordinar el método de pago y la entrega, pulsa el botón siguiente para enviar los datos a nuestro WhatsApp oficial:
        </p>

        <!-- Prominent WhatsApp Action & PDF Download CTA -->
        <div style="display: flex; gap: var(--space-4); justify-content: center; flex-wrap: wrap; margin-bottom: var(--space-8);">
            <a href="{{ $whatsAppUrl }}" 
               target="_blank" 
               rel="noopener" 
               class="btn btn-lg" 
               style="background-color: #25D366; color: #FFFFFF; border: 1px solid #25D366; font-size: 1.05rem; padding: 1.1rem 2.2rem; box-shadow: 0 8px 25px rgba(37, 211, 102, 0.35);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                Confirmar Pedido por WhatsApp
            </a>

            <a href="{{ route('order.pdf', $order->order_code) }}" 
               class="btn btn-outline btn-lg" 
               style="font-size: 1.05rem; padding: 1.1rem 2rem; display: inline-flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Descargar Comprobante PDF
            </a>
        </div>

        <!-- Itemized Order Details -->
        <div style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-6); text-align: left; margin-bottom: var(--space-8);">
            <h3 style="font-family: var(--font-display); font-size: 1.25rem; margin-bottom: var(--space-4); border-bottom: 1px solid var(--color-border-light); padding-bottom: var(--space-2);">
                Resumen de la Orden
            </h3>

            <div style="margin-bottom: var(--space-4);">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between" style="padding: 6px 0; font-size: var(--text-sm);">
                        <span>{{ $item->quantity }}x {{ $item->product_name }} @if($item->variant_details) ({{ $item->variant_details }}) @endif</span>
                        <strong>${{ number_format($item->total_price, 2) }}</strong>
                    </div>
                @endforeach
            </div>

            <div style="border-top: 1px solid var(--color-border-light); padding-top: var(--space-3); font-size: var(--text-sm);">
                <div class="flex justify-between" style="margin-bottom: 4px;">
                    <span style="color: var(--color-text-muted);">Entrega:</span>
                    <span>{{ $order->delivery_method_label }} ({{ $order->delivery_city }})</span>
                </div>
                <div class="flex justify-between" style="margin-bottom: 4px;">
                    <span style="color: var(--color-text-muted);">Dirección:</span>
                    <span>{{ $order->delivery_address }}</span>
                </div>
                @if($order->packaging_name)
                    <div class="flex justify-between" style="margin-bottom: 4px;">
                        <span style="color: var(--color-text-muted);">Empaque:</span>
                        <span>{{ $order->packaging_name }} ({{ (float)$order->packaging_price > 0 ? '+$' . number_format($order->packaging_price, 2) : 'Incluido' }})</span>
                    </div>
                @endif
                @if($order->is_gift)
                    <div class="flex justify-between" style="margin-bottom: 4px; color: var(--color-primary-dark);">
                        <span>🎁 Regalo para:</span>
                        <span>{{ $order->gift_recipient_name ?? 'Destinataria' }}</span>
                    </div>
                @endif
                <div class="flex justify-between" style="font-size: 1.15rem; font-weight: 700; margin-top: 10px; padding-top: 8px; border-top: 2px solid var(--color-border);">
                    <span>Total a Pagar:</span>
                    <span style="color: var(--color-primary-dark);">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-outline">
            Volver a la Página Principal
        </a>
    </div>
</div>
@endsection
