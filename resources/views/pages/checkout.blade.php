@extends('layouts.app')

@section('title', 'Finalizar Orden | MORAIA')
@section('meta_description', 'Registra tu orden en Moraia y coordina la entrega directamente por WhatsApp.')

@section('content')
<div class="container section-padding">
    <nav class="breadcrumbs" aria-label="Ruta de navegación">
        <a href="{{ route('home') }}">Inicio</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('cart.index') }}">Bolsa de Compras</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Finalizar Orden</span>
    </nav>

    <div style="margin-bottom: var(--space-8);">
        <h1 style="font-size: clamp(2rem, 4vw, 2.75rem); margin-bottom: var(--space-2);">Completar tu Orden</h1>
        <p style="color: var(--color-text-muted);">
            Ingresa tus datos de contacto y entrega. No necesitas pagar online: coordinaremos el pago y envío contigo por WhatsApp.
        </p>
    </div>

    @if($errors->any())
        <div style="background-color: var(--color-error-bg); border: 1px solid var(--color-error); border-radius: var(--radius-sm); padding: var(--space-4); margin-bottom: var(--space-6); color: var(--color-error); font-size: var(--text-sm);">
            <strong>Por favor revisa los campos requeridos:</strong>
            <ul style="margin-top: 5px; list-style: disc; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="checkout-grid">
            <!-- Left Column: Form Details -->
            <div>
                <!-- 1. Customer Details -->
                <div class="checkout-card" style="margin-bottom: var(--space-6);">
                    <h2 class="checkout-card-title">
                        <span>1. Datos de Contacto</span>
                    </h2>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                        <div class="form-group">
                            <label class="form-label" for="customer_name">Nombre *</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-input" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="customer_lastname">Apellido *</label>
                            <input type="text" id="customer_lastname" name="customer_lastname" class="form-input" value="{{ old('customer_lastname') }}" required>
                        </div>
                    </div>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                        <div class="form-group">
                            <label class="form-label" for="customer_whatsapp">WhatsApp *</label>
                            <input type="tel" id="customer_whatsapp" name="customer_whatsapp" class="form-input" placeholder="Ej: 04121234567" value="{{ old('customer_whatsapp') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="customer_phone">Teléfono de Contacto *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" class="form-input" placeholder="Ej: 04141234567" value="{{ old('customer_phone') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="customer_email">Correo Electrónico (Opcional)</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-input" placeholder="tu@email.com" value="{{ old('customer_email') }}">
                    </div>
                </div>

                <!-- 2. Delivery Options & Address -->
                <div class="checkout-card" style="margin-bottom: var(--space-6);">
                    <h2 class="checkout-card-title">
                        <span>2. Método de Entrega & Dirección</span>
                    </h2>

                    <div class="form-group">
                        <label class="form-label">Modalidad de Envío *</label>
                        <div style="display: flex; flex-direction: column; gap: var(--space-3); margin-top: 8px;">
                            <label class="form-check" style="border: 1px solid var(--color-border); padding: 12px; border-radius: var(--radius-sm); background-color: #FFFFFF;">
                                <input type="radio" name="delivery_method" value="delivery_caracas" {{ old('delivery_method', 'delivery_caracas') === 'delivery_caracas' ? 'checked' : '' }} onchange="updateShipping(3.00)">
                                <div>
                                    <strong style="display: block; font-size: var(--text-sm);">Delivery propio en Caracas (+$3.00)</strong>
                                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);">Entrega en 24h directamente a tu puerta.</span>
                                </div>
                            </label>

                            <label class="form-check" style="border: 1px solid var(--color-border); padding: 12px; border-radius: var(--radius-sm); background-color: #FFFFFF;">
                                <input type="radio" name="delivery_method" value="envio_nacional" {{ old('delivery_method') === 'envio_nacional' ? 'checked' : '' }} onchange="updateShipping(0.00)">
                                <div>
                                    <strong style="display: block; font-size: var(--text-sm);">Envío Nacional (Cobro a Destino)</strong>
                                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);">A través de MRW, Zoom o Tealca a toda Venezuela.</span>
                                </div>
                            </label>

                            <label class="form-check" style="border: 1px solid var(--color-border); padding: 12px; border-radius: var(--radius-sm); background-color: #FFFFFF;">
                                <input type="radio" name="delivery_method" value="pickup" {{ old('delivery_method') === 'pickup' ? 'checked' : '' }} onchange="updateShipping(0.00)">
                                <div>
                                    <strong style="display: block; font-size: var(--text-sm);">Pick-up / Retiro Previo Acuerdo ($0.00)</strong>
                                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);">Punto de entrega acordado en Caracas.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="delivery_city">Ciudad / Municipio *</label>
                        <input type="text" id="delivery_city" name="delivery_city" class="form-input" placeholder="Ej: Caracas - Chacao / Valencia / Maracaibo" value="{{ old('delivery_city') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="delivery_address">Dirección Completa / Agencia de Envío *</label>
                        <textarea id="delivery_address" name="delivery_address" class="form-textarea" rows="3" placeholder="Calle, edificio, urbanización, punto de referencia o agencia MRW/Zoom..." required>{{ old('delivery_address') }}</textarea>
                    </div>
                </div>

                <!-- 3. Gift Options (Personalized Box / Card) -->
                <div class="checkout-card" style="margin-bottom: var(--space-6);">
                    <h2 class="checkout-card-title">
                        <span>3. ¿Este pedido es un Regalo?</span>
                    </h2>

                    <div class="form-group">
                        <label class="form-check" style="cursor: pointer;">
                            <input type="checkbox" name="is_gift" id="is_gift_checkbox" value="1" {{ old('is_gift') ? 'checked' : '' }} onchange="toggleGiftFields()">
                            <div>
                                <strong style="color: var(--color-primary-dark);">🎁 Sí, deseo incluir empaque de regalo y tarjeta personalizada</strong>
                                <span style="display: block; font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px;">
                                    Prepararemos tu pedido con lazo de satén y tarjeta escrita para la destinataria.
                                </span>
                            </div>
                        </label>
                    </div>

                    <div id="gift-details-wrap" style="display: {{ old('is_gift') ? 'block' : 'none' }}; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--color-border);">
                        <div class="form-group">
                            <label class="form-label" for="gift_recipient_name">Nombre de la Destinataria</label>
                            <input type="text" id="gift_recipient_name" name="gift_recipient_name" class="form-input" placeholder="¿Para quién es el regalo?" value="{{ old('gift_recipient_name') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="gift_card_message">Mensaje para la Tarjeta de Regalo</label>
                            <textarea id="gift_card_message" name="gift_card_message" class="form-textarea" rows="3" placeholder="Escribe el mensaje que deseas que incluyamos en la tarjeta...">{{ old('gift_card_message') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 4. Notes -->
                <div class="checkout-card">
                    <h2 class="checkout-card-title">
                        <span>4. Notas Adicionales</span>
                    </h2>
                    <div class="form-group" style="margin-bottom: 0;">
                        <textarea name="customer_notes" class="form-textarea" rows="2" placeholder="Instrucciones especiales para la entrega o detalles de tu pedido...">{{ old('customer_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sticky Summary -->
            <div>
                <div class="checkout-card checkout-summary">
                    <h3 class="checkout-card-title">Tu Pedido</h3>

                    <div style="max-height: 280px; overflow-y: auto; margin-bottom: var(--space-4); padding-right: 5px;">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between" style="padding: 8px 0; border-bottom: 1px solid var(--color-border-light); font-size: var(--text-sm);">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 48px; height: 48px; aspect-ratio: 1 / 1; object-fit: contain; background-color: var(--color-surface-soft); border-radius: var(--radius-xs); border: 1px solid var(--color-border-light);">
                                    <div>
                                        <div style="font-weight: 600; color: var(--color-text);">{{ $item['name'] }}</div>
                                        <div style="font-size: var(--text-xs); color: var(--color-text-muted);">
                                            Cant: {{ $item['quantity'] }} @if(!empty($item['variant_name'])) | {{ $item['variant_name'] }} @endif
                                        </div>
                                    </div>
                                </div>
                                <div style="font-weight: 700; color: var(--color-text);">
                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Envío</span>
                        <span id="summary-shipping">${{ number_format(old('delivery_method', 'delivery_caracas') === 'delivery_caracas' ? $shippingCaracas : 0, 2) }}</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total Final</span>
                        <span id="summary-total" style="color: var(--color-primary-dark);">
                            ${{ number_format($subtotal + (old('delivery_method', 'delivery_caracas') === 'delivery_caracas' ? $shippingCaracas : 0), 2) }}
                        </span>
                    </div>

                    <div style="margin-top: var(--space-6);">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" style="font-size: 1rem; padding: 1.15rem;">
                            Registrar Pedido &rarr;
                        </button>
                    </div>

                    <div style="margin-top: var(--space-6); background-color: var(--color-surface-soft); padding: 12px; border-radius: var(--radius-sm); font-size: 0.75rem; color: var(--color-text-muted); line-height: 1.5; border: 1px solid var(--color-border);">
                        🌸 <strong>Paso siguiente:</strong> Al registrar tu pedido, serás redirigido a la pantalla de éxito con el botón directo para enviar los detalles por WhatsApp a <strong>+58 412 020 6548</strong> y acordar el pago.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};

    function updateShipping(fee) {
        document.getElementById('summary-shipping').textContent = '$' + fee.toFixed(2);
        const total = subtotal + fee;
        document.getElementById('summary-total').textContent = '$' + total.toFixed(2);
    }

    function toggleGiftFields() {
        const check = document.getElementById('is_gift_checkbox');
        const wrap = document.getElementById('gift-details-wrap');
        wrap.style.display = check.checked ? 'block' : 'none';
    }
</script>
@endpush
@endsection
