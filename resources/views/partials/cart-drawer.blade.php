@php
    $cart = session()->get('cart', []);
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += ($item['price'] * $item['quantity']);
    }
@endphp

<div class="drawer drawer-cart" role="dialog" aria-label="Tu Bolsa y Checkout">
    <!-- Drawer Header -->
    <div class="drawer-header">
        <div class="drawer-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <span id="drawer-main-title">Tu Bolsa de Compras</span>
        </div>
        <button type="button" class="drawer-close drawer-cart-close" aria-label="Cerrar bolsa">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Stepper Progress Tracker Bar -->
    <div class="drawer-stepper-wrap">
        <div class="drawer-stepper">
            <!-- Step 1: Pedido -->
            <div class="drawer-step-item active" data-step-target="1" id="step-indicator-1" onclick="goToDrawerStep(1)">
                <div class="step-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
                <span class="step-label">Pedido</span>
            </div>

            <div class="step-connector" id="step-connector-1-2"></div>

            <!-- Step 2: Checkout -->
            <div class="drawer-step-item" data-step-target="2" id="step-indicator-2" onclick="goToDrawerStep(2)">
                <div class="step-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-6 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6.75a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6.75v10.5a1.5 1.5 0 0 0 1.5 1.5Z" />
                    </svg>
                </div>
                <span class="step-label">Checkout</span>
            </div>

            <div class="step-connector" id="step-connector-2-3"></div>

            <!-- Step 3: Confirmar -->
            <div class="drawer-step-item" data-step-target="3" id="step-indicator-3">
                <div class="step-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <span class="step-label">Confirmar</span>
            </div>
        </div>
    </div>

    <!-- ==========================================
         STEP 1: LISTA DE PRODUCTOS & SUBTOTAL
         ========================================== -->
    <div class="drawer-step-panel active" id="drawer-panel-1">
        <div class="drawer-body drawer-cart-items">
            @include('partials.cart-drawer-items', ['cart' => $cart])
        </div>

        <div class="drawer-footer" id="drawer-step-1-footer" style="display: {{ !empty($cart) ? 'block' : 'none' }};">
            <div class="drawer-price-summary" style="margin-bottom: var(--space-4);">
                <div class="flex items-center justify-between">
                    <span style="font-size: var(--text-sm); font-weight: 600; text-transform: uppercase; letter-spacing: var(--tracking-wide); color: var(--color-text-muted);">Subtotal</span>
                    <span class="drawer-cart-subtotal" style="font-size: 1.35rem; font-weight: 700; color: var(--color-text);">
                        ${{ number_format($subtotal, 2) }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <button type="button" class="btn btn-primary btn-block" onclick="goToDrawerStep(2)">
                    <span>Continuar al Checkout &rarr;</span>
                </button>
                <button type="button" class="btn btn-outline btn-block btn-sm" onclick="clearCartAjax()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Vaciar Bolsa</span>
                </button>
            </div>
            <div style="font-size: 0.72rem; color: var(--color-text-muted); text-align: center; margin-top: 8px;">
                🔒 Confirmación directa vía WhatsApp sin cargos ocultos.
            </div>
        </div>
    </div>

    <!-- ==========================================
         STEP 2: CHECKOUT & DATOS DE ENTREGA
         ========================================== -->
    <div class="drawer-step-panel" id="drawer-panel-2">
        <div class="drawer-body">
            <form id="drawer-checkout-form" onsubmit="submitDrawerCheckout(event)">
                @csrf
                <div class="drawer-form-section-title">Datos del Cliente</div>
                <div class="grid-form-2" style="margin-bottom: var(--space-3); gap: var(--space-2);">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.76rem;">Nombre *</label>
                        <input type="text" name="customer_name" class="form-input" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="Tu nombre" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 0.76rem;">Apellido *</label>
                        <input type="text" name="customer_lastname" class="form-input" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="Tu apellido" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: var(--space-3);">
                    <label class="form-label" style="font-size: 0.76rem;">Teléfono / WhatsApp *</label>
                    <input type="tel" name="customer_whatsapp" id="drawer_whatsapp" class="form-input" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="Ej: 04121234567" required oninput="document.getElementById('drawer_phone').value = this.value">
                    <input type="hidden" name="customer_phone" id="drawer_phone">
                </div>

                <div class="form-group" style="margin-bottom: var(--space-4);">
                    <label class="form-label" style="font-size: 0.76rem;">Correo Electrónico (Opcional)</label>
                    <input type="email" name="customer_email" class="form-input" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="correo@ejemplo.com">
                </div>

                <div class="drawer-form-section-title">Método de Entrega</div>
                <div class="drawer-delivery-options" style="margin-bottom: var(--space-3);">
                    <label class="drawer-delivery-card active" onclick="selectDrawerDelivery('delivery_caracas', 3.00, this)">
                        <input type="radio" name="delivery_method" value="delivery_caracas" checked style="display:none;">
                        <div class="flex items-center justify-between">
                            <span style="font-weight: 600; font-size: 0.85rem;">🛵 Delivery en Caracas</span>
                            <span style="font-weight: 700; color: var(--color-primary); font-size: 0.85rem;">+$3.00</span>
                        </div>
                        <span style="font-size: 0.72rem; color: var(--color-text-muted);">Mensajería propia en 24h</span>
                    </label>

                    <label class="drawer-delivery-card" onclick="selectDrawerDelivery('envio_nacional', 0.00, this)">
                        <input type="radio" name="delivery_method" value="envio_nacional" style="display:none;">
                        <div class="flex items-center justify-between">
                            <span style="font-weight: 600; font-size: 0.85rem;">📦 Envíos Nacionales</span>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted);">Cobro Destino</span>
                        </div>
                        <span style="font-size: 0.72rem; color: var(--color-text-muted);">MRW / Zoom / Tealca asegurado</span>
                    </label>

                    <label class="drawer-delivery-card" onclick="selectDrawerDelivery('pickup', 0.00, this)">
                        <input type="radio" name="delivery_method" value="pickup" style="display:none;">
                        <div class="flex items-center justify-between">
                            <span style="font-weight: 600; font-size: 0.85rem;">📍 Retiro Personal</span>
                            <span style="font-weight: 700; color: #2E7D32; font-size: 0.85rem;">Gratis</span>
                        </div>
                        <span style="font-size: 0.72rem; color: var(--color-text-muted);">Caracas (Previa coordinación)</span>
                    </label>
                </div>

                <div class="form-group" style="margin-bottom: var(--space-3);">
                    <label class="form-label" style="font-size: 0.76rem;">Ciudad / Municipio *</label>
                    <input type="text" name="delivery_city" class="form-input" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="Ej: Caracas, Baruta / Valencia" required>
                </div>

                <div class="form-group" style="margin-bottom: var(--space-4);">
                    <label class="form-label" style="font-size: 0.76rem;">Dirección Exacta o Agencia *</label>
                    <textarea name="delivery_address" class="form-textarea" rows="2" style="padding: 0.55rem 0.75rem; font-size: 0.85rem;" placeholder="Calle, edificio, casa o nombre de la agencia" required></textarea>
                </div>

                <div style="background-color: var(--color-surface-soft); padding: var(--space-3); border-radius: var(--radius-xs); border: 1px solid var(--color-border); margin-bottom: var(--space-4);">
                    <label class="flex items-center gap-2" style="cursor: pointer; margin-bottom: 0;">
                        <input type="checkbox" name="is_gift" value="1" id="drawer_is_gift" onchange="toggleDrawerGift(this.checked)">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-text);">🎁 ¿Es para regalo? (Lazo y tarjeta gratis)</span>
                    </label>

                    <div id="drawer-gift-fields" style="display: none; margin-top: var(--space-3); padding-top: var(--space-2); border-top: 1px dashed var(--color-border);">
                        <div class="form-group" style="margin-bottom: var(--space-2);">
                            <label class="form-label" style="font-size: 0.75rem;">Para quién es:</label>
                            <input type="text" name="gift_recipient_name" class="form-input" style="padding: 0.45rem 0.65rem; font-size: 0.8rem;" placeholder="Nombre de la persona especial">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem;">Mensaje para la tarjeta:</label>
                            <textarea name="gift_card_message" class="form-textarea" rows="2" style="padding: 0.45rem 0.65rem; font-size: 0.8rem;" placeholder="Escribe aquí tu dedicatoria..."></textarea>
                        </div>
                    </div>
                </div>

                <div id="drawer-checkout-error" class="form-error-msg" style="display: none; margin-bottom: var(--space-3); color: #D32F2F; font-size: 0.8rem; padding: 6px 10px; background-color: #FFEBEE; border-radius: var(--radius-xs);"></div>

                <!-- Resumen de Costos y Acciones dentro del formulario -->
                <div class="drawer-price-summary" style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-xs); padding: 12px 14px; margin-top: var(--space-2); margin-bottom: var(--space-4);">
                    <div class="flex items-center justify-between" style="font-size: 0.82rem; margin-bottom: 3px;">
                        <span style="color: var(--color-text-muted);">Subtotal productos:</span>
                        <span class="drawer-cart-subtotal" style="font-weight: 600;">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between" style="font-size: 0.82rem; margin-bottom: 6px;">
                        <span style="color: var(--color-text-muted);">Envío:</span>
                        <span id="drawer-shipping-fee-display" style="font-weight: 600;">$3.00</span>
                    </div>
                    <div class="flex items-center justify-between" style="font-size: 1.15rem; font-weight: 700; color: var(--color-text); padding-top: 6px; border-top: 1px solid var(--color-border-light);">
                        <span>Total a Pagar:</span>
                        <span id="drawer-total-display" style="color: var(--color-primary);">${{ number_format($subtotal + 3.00, 2) }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2" style="margin-bottom: var(--space-4);">
                    <button type="submit" id="drawer-submit-btn" class="btn btn-primary btn-block">
                        <span>Confirmar Pedido por WhatsApp &rarr;</span>
                    </button>
                    <button type="button" class="btn btn-outline btn-block btn-sm" onclick="goToDrawerStep(1)">
                        &larr; Volver a la Bolsa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         STEP 3: CONFIRMACIÓN / ÉXITO
         ========================================== -->
    <div class="drawer-step-panel" id="drawer-panel-3">
        <div class="drawer-body text-center" style="padding: 24px 16px;">
            <div class="order-success-icon-wrap" style="width: 64px; height: 64px; margin: 0 auto var(--space-4) auto; background: rgba(200, 116, 126, 0.12); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-primary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>

            <span class="badge badge-rose" style="margin-bottom: var(--space-2);">Pedido Registrado</span>
            <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 6px; color: var(--color-text);">
                ¡Gracias por tu compra!
            </h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: var(--space-5);">
                Tu orden ha sido guardada. Haz clic en el botón verde abajo para abrir WhatsApp y concretar tu pago con atención inmediata:
            </p>

            <div style="background-color: var(--color-surface-soft); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: var(--space-4); margin-bottom: var(--space-5); text-align: left;">
                <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                    <span style="font-size: 0.8rem; color: var(--color-text-muted);">Número de Orden:</span>
                    <strong id="drawer-order-code-display" style="font-size: 0.95rem; color: var(--color-primary);">#MOR-2026-XXXX</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span style="font-size: 0.8rem; color: var(--color-text-muted);">Total:</span>
                    <strong id="drawer-order-total-display" style="font-size: 1.1rem; color: var(--color-text);">$0.00</strong>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <a href="#" id="drawer-whatsapp-btn" target="_blank" rel="noopener" class="btn btn-primary btn-block btn-lg" style="background-color: #25D366; border-color: #25D366; color: #FFFFFF; font-weight: 700;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Finalizar en WhatsApp</span>
                </a>
                <a href="#" id="drawer-pdf-btn" class="btn btn-outline btn-block btn-sm" style="display: none; align-items: center; justify-content: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Descargar Comprobante PDF</span>
                </a>
                <button type="button" class="btn btn-outline btn-block btn-sm" onclick="closeMiniCart(); location.reload();">
                    Cerrar y Seguir Comprando
                </button>
            </div>
        </div>
    </div>
</div>
