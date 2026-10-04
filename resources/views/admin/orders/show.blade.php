@extends('layouts.admin')

@section('page_title', 'Detalle de Orden #' . $order->order_code)

@section('admin_content')
<div class="grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
    <!-- Left Column: Order Items & Customer -->
    <div>
        <!-- Order Header Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2 class="admin-card-title">Orden #{{ $order->order_code }}</h2>
                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);">
                        Registrada el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }} ({{ $order->created_at->diffForHumans() }})
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.pdf', $order->id) }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Descargar PDF
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">&larr; Volver al Listado</a>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        @if($item->product_image)
                                            <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" style="width: 48px; height: 56px; object-fit: cover; border-radius: var(--radius-xs); border: 1px solid var(--color-border-light); flex-shrink: 0; margin-right: 6px;">
                                        @else
                                            <div style="width: 48px; height: 56px; border-radius: var(--radius-xs); background: var(--color-surface-soft); border: 1px solid var(--color-border-light); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); flex-shrink: 0; margin-right: 6px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <strong style="color: var(--color-text); font-weight: 600; display: block; line-height: 1.3;">{{ $item->product_name }}</strong>
                                            @if($item->variant_details)
                                                <div style="font-size: var(--text-xs); color: var(--color-primary-dark); font-weight: 500; margin-top: 3px;">
                                                    {{ $item->variant_details }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <strong>${{ number_format($item->total_price, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Breakdown -->
            <div style="margin-top: var(--space-6); padding-top: var(--space-4); border-top: 1px solid var(--color-border-light); max-width: 320px; margin-left: auto;">
                <div class="flex justify-between" style="font-size: var(--text-sm); margin-bottom: 6px;">
                    <span style="color: var(--color-text-muted);">Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->packaging_name)
                    <div class="flex justify-between" style="font-size: var(--text-sm); margin-bottom: 6px;">
                        <span style="color: var(--color-text-muted);">Empaque ({{ $order->packaging_name }}):</span>
                        <span>{{ (float)$order->packaging_price > 0 ? '$' . number_format($order->packaging_price, 2) : 'Incluido' }}</span>
                    </div>
                @endif
                <div class="flex justify-between" style="font-size: var(--text-sm); margin-bottom: 6px;">
                    <span style="color: var(--color-text-muted);">Envío ({{ $order->delivery_method_label }}):</span>
                    <span>${{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="flex justify-between" style="font-size: 1.25rem; font-weight: 700; color: var(--color-primary-dark); padding-top: 8px; border-top: 2px solid var(--color-border);">
                    <span>Total:</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Packaging Selection Card -->
        @if($order->packaging_name)
            <div class="admin-card" style="border-left: 4px solid var(--color-primary-dark);">
                <div class="flex items-center justify-between" style="margin-bottom: var(--space-2);">
                    <h3 style="font-family: var(--font-display); font-size: 1.3rem; color: var(--color-primary-dark); margin: 0;">
                        📦 Presentación & Empaque Seleccionado
                    </h3>
                    @if((float)$order->packaging_price > 0)
                        <span class="badge" style="background: var(--color-primary-light); color: var(--color-primary-dark);">
                            +${{ number_format($order->packaging_price, 2) }} US$
                        </span>
                    @else
                        <span class="badge badge-confirmed">Incluido</span>
                    @endif
                </div>
                <div style="font-size: var(--text-sm); color: var(--color-text);">
                    <strong>Tipo de Empaque:</strong> {{ $order->packaging_name }}
                    @if($order->packaging && $order->packaging->capacity)
                        <span style="color: var(--color-text-muted); font-size: var(--text-xs); margin-left: 6px;">
                            (Capacidad: {{ $order->packaging->capacity }})
                        </span>
                    @endif
                    @if($order->packaging && $order->packaging->description)
                        <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 4px; font-style: italic;">
                            {{ $order->packaging->description }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Gift Information Card (if applicable) -->
        @if($order->is_gift)
            <div class="admin-card" style="border-left: 4px solid var(--color-primary);">
                <h3 style="font-family: var(--font-display); font-size: 1.3rem; margin-bottom: var(--space-3); color: var(--color-primary-dark);">
                    🎁 Información de Regalo Personalizado
                </h3>
                <div style="font-size: var(--text-sm); line-height: 1.6;">
                    <div style="margin-bottom: 8px;">
                        <strong>Destinataria del Regalo:</strong> {{ $order->gift_recipient_name ?? 'No especificada' }}
                    </div>
                    @if($order->gift_card_message)
                        <div style="background-color: var(--color-surface-soft); padding: 12px; border-radius: var(--radius-xs); border: 1px dashed var(--color-border);">
                            <strong>💌 Mensaje escrito para la tarjeta:</strong>
                            <p style="margin-top: 4px; font-style: italic; color: var(--color-text-light);">
                                "{{ $order->gift_card_message }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Customer Notes -->
        @if($order->customer_notes)
            <div class="admin-card">
                <h4 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; margin-bottom: 8px;">
                    Notas del Cliente
                </h4>
                <p style="color: var(--color-text-light); font-size: var(--text-sm);">
                    {{ $order->customer_notes }}
                </p>
            </div>
        @endif
    </div>

    <!-- Right Column: Status & WhatsApp Action -->
    <div>
        <!-- Instant WhatsApp Action -->
        <div class="admin-card" style="background-color: #FAF5F2; border-color: var(--color-primary-light);">
            <h3 style="font-family: var(--font-display); font-size: 1.3rem; margin-bottom: 8px;">
                Comunicación con Cliente
            </h3>
            <p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-bottom: var(--space-4);">
                Contacta directamente al cliente con el resumen de la orden preformateado.
            </p>

            <a href="{{ $whatsAppCustomerUrl }}" 
               target="_blank" 
               rel="noopener" 
               class="btn btn-block" 
               style="background-color: #25D366; color: #FFFFFF; border: 1px solid #25D366; font-size: 0.95rem; margin-bottom: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                Contactar por WhatsApp
            </a>
        </div>

        <!-- Status Form -->
        <div class="admin-card">
            <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; margin-bottom: var(--space-4);">
                Estado del Pedido
            </h3>

            <div style="margin-bottom: var(--space-4);">
                <span class="badge {{ $order->status_badge_class }}" style="font-size: 0.85rem; padding: 6px 12px;">
                    Estado Actual: {{ $order->status }}
                </span>
            </div>

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label class="form-label" for="status">Cambiar Estado:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Pendiente" {{ in_array($order->status, ['Pendiente', 'Nueva', 'Preparando']) ? 'selected' : '' }}>Pendiente</option>
                        <option value="Confirmada" {{ in_array($order->status, ['Confirmada', 'Lista']) ? 'selected' : '' }}>Confirmada</option>
                        <option value="Entregada" {{ $order->status === 'Entregada' ? 'selected' : '' }}>Entregada</option>
                        <option value="Cancelada" {{ $order->status === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary btn-block btn-sm">
                    Actualizar Estado
                </button>
            </form>
        </div>

        <!-- Customer Card -->
        <div class="admin-card">
            <h3 style="font-size: var(--text-sm); font-weight: 700; text-transform: uppercase; margin-bottom: var(--space-4);">
                Datos del Cliente
            </h3>
            <div style="font-size: var(--text-sm); line-height: 1.6; display: flex; flex-direction: column; gap: 6px;">
                <div><strong>Nombre:</strong> {{ $order->full_name }}</div>
                <div><strong>WhatsApp:</strong> {{ $order->customer_whatsapp }}</div>
                <div><strong>Teléfono:</strong> {{ $order->customer_phone }}</div>
                @if($order->customer_email)
                    <div><strong>Email:</strong> {{ $order->customer_email }}</div>
                @endif
                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--color-border-light);">
                    <strong>Modalidad:</strong> {{ $order->delivery_method_label }}<br>
                    <strong>Ciudad:</strong> {{ $order->delivery_city }}<br>
                    <strong>Dirección:</strong> {{ $order->delivery_address }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
