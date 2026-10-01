<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de Orden #{{ $order->order_code }} - MORAIA</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2D2D2D;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #8A4A58;
            padding-bottom: 12px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            color: #7B3F50;
            letter-spacing: 3px;
            margin: 0;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 9px;
            color: #7A7A7A;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .receipt-badge-title {
            font-size: 13px;
            font-weight: bold;
            color: #7B3F50;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .order-code {
            font-size: 13px;
            font-weight: bold;
            color: #222222;
            margin-top: 2px;
        }
        .order-date {
            font-size: 10px;
            color: #666666;
            margin-top: 2px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .badge-pending { background-color: #D9822B; color: #FFFFFF; }
        .badge-confirmed { background-color: #4E8B71; color: #FFFFFF; }
        .badge-delivered { background-color: #2E8B57; color: #FFFFFF; }
        .badge-cancelled { background-color: #C05C5C; color: #FFFFFF; }

        /* Two Columns Section */
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 20px;
        }
        .info-card {
            background-color: #FAF6F4;
            border: 1px solid #EAE0D8;
            border-radius: 4px;
            padding: 10px 12px;
            vertical-align: top;
            width: 50%;
        }
        .card-heading {
            font-size: 10px;
            font-weight: bold;
            color: #7B3F50;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #E2D5CC;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .info-row {
            margin-bottom: 4px;
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #555555;
        }

        /* Products Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #7B3F50;
            color: #FFFFFF;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 7px 8px;
            text-align: left;
        }
        .items-table th.text-center { text-align: center; }
        .items-table th.text-right { text-align: right; }
        .items-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #EAE0D8;
            font-size: 10px;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) td {
            background-color: #FCFAF8;
        }
        .item-name {
            font-weight: bold;
            color: #222222;
        }
        .item-variant {
            font-size: 9px;
            color: #7B3F50;
            margin-top: 2px;
        }

        /* Totals Block */
        .summary-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-table {
            width: 250px;
            border-collapse: collapse;
            margin-left: auto;
            border: 1px solid #EAE0D8;
            background-color: #FAF6F4;
            border-radius: 4px;
        }
        .summary-table td {
            padding: 5px 10px;
            font-size: 10px;
        }
        .summary-total-row td {
            background-color: #7B3F50;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            padding: 7px 10px;
        }

        /* Notes Box */
        .notes-box {
            background-color: #FDFBF7;
            border: 1px dashed #D6C2B4;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 9.5px;
        }

        /* Footer */
        .footer {
            border-top: 1px solid #EAE0D8;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #777777;
            margin-top: 20px;
        }
        .footer-brand {
            font-weight: bold;
            color: #7B3F50;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">MORAIA</div>
                <div class="brand-subtitle">Lencería Fina &amp; Detalles Exclusivos</div>
                <div style="font-size: 9px; color: #888888; margin-top: 3px;">
                    Caracas, Venezuela &bull; WhatsApp: +58 412 020 6548
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div class="receipt-badge-title">Comprobante de Pedido</div>
                <div class="order-code">#{{ $order->order_code }}</div>
                <div class="order-date">
                    Fecha: {{ $order->created_at ? $order->created_at->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}
                </div>
                <div>
                    @php
                        $badgeClass = match ($order->status) {
                            'Pendiente', 'Nueva' => 'badge-pending',
                            'Confirmada', 'Preparando', 'Lista' => 'badge-confirmed',
                            'Entregada' => 'badge-delivered',
                            'Cancelada' => 'badge-cancelled',
                            default => 'badge-pending',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        Estado: {{ $order->status }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Customer & Delivery Two Columns -->
    <table class="info-table">
        <tr>
            <!-- Customer Card -->
            <td class="info-card">
                <div class="card-heading">Datos del Cliente</div>
                <div class="info-row">
                    <span class="info-label">Nombre:</span> {{ $order->full_name }}
                </div>
                <div class="info-row">
                    <span class="info-label">WhatsApp:</span> {{ $order->customer_whatsapp }}
                </div>
                @if($order->customer_phone && $order->customer_phone !== $order->customer_whatsapp)
                    <div class="info-row">
                        <span class="info-label">Teléfono Alt.:</span> {{ $order->customer_phone }}
                    </div>
                @endif
                @if($order->customer_email)
                    <div class="info-row">
                        <span class="info-label">Email:</span> {{ $order->customer_email }}
                    </div>
                @endif
            </td>

            <!-- Delivery Card -->
            <td class="info-card">
                <div class="card-heading">Información de Entrega</div>
                <div class="info-row">
                    <span class="info-label">Modalidad:</span> {{ $order->delivery_method_label }}
                </div>
                <div class="info-row">
                    <span class="info-label">Ciudad:</span> {{ $order->delivery_city }}
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección:</span> {{ $order->delivery_address }}
                </div>
                @if($order->is_gift)
                    <div class="info-row" style="color: #7B3F50; font-weight: bold; margin-top: 4px;">
                        <span>🎁 Es un Regalo para: {{ $order->gift_recipient_name ?? 'Destinataria' }}</span>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th>Descripción del Producto</th>
                <th style="width: 75px;" class="text-right">Precio Unit.</th>
                <th style="width: 45px;" class="text-center">Cant.</th>
                <th style="width: 80px;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalQty = 0; @endphp
            @foreach($order->items as $index => $item)
                @php $totalQty += $item->quantity; @endphp
                <tr>
                    <td class="text-center" style="color: #888888;">{{ $index + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->variant_details)
                            <div class="item-variant">{{ $item->variant_details }}</div>
                        @endif
                    </td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-right font-bold">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary / Totals -->
    <table class="summary-wrapper">
        <tr>
            <td style="vertical-align: top; width: 55%;">
                @if($order->is_gift && $order->gift_card_message)
                    <div class="notes-box">
                        <strong style="color: #7B3F50;">💌 Mensaje de la tarjeta de regalo:</strong><br>
                        <em>"{{ $order->gift_card_message }}"</em>
                    </div>
                @endif

                @if($order->customer_notes)
                    <div class="notes-box">
                        <strong>Notas adicionales del pedido:</strong><br>
                        {{ $order->customer_notes }}
                    </div>
                @endif

                <div style="font-size: 9px; color: #777777; line-height: 1.5; padding-right: 15px;">
                    <strong>Coordinación de Pago:</strong> Para concretar tu compra por Pago Móvil, Zelle, Transferencia o Efectivo, envía este comprobante o el código de tu orden a nuestro WhatsApp oficial.
                </div>
            </td>
            <td style="vertical-align: top; width: 45%;">
                <table class="summary-table">
                    <tr>
                        <td style="color: #666666;">Total Unidades:</td>
                        <td style="text-align: right; font-weight: bold;">{{ $totalQty }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666666;">Subtotal:</td>
                        <td style="text-align: right; font-weight: bold;">${{ number_format($order->subtotal, 2) }} US$</td>
                    </tr>
                    <tr>
                        <td style="color: #666666;">Envío ({{ $order->delivery_method_label }}):</td>
                        <td style="text-align: right; font-weight: bold;">
                            @if($order->shipping_fee > 0)
                                ${{ number_format($order->shipping_fee, 2) }} US$
                            @elseif($order->delivery_method === 'envio_nacional')
                                Cobro en Destino
                            @else
                                $0.00 US$
                            @endif
                        </td>
                    </tr>
                    <tr class="summary-total-row">
                        <td>TOTAL A PAGAR:</td>
                        <td style="text-align: right;">${{ number_format($order->total, 2) }} US$</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <span class="footer-brand">MORAIA</span> &bull; Belleza, Lencería Fina &amp; Momentos Especiales<br>
        Atención y confirmaciones vía WhatsApp: +58 412 020 6548 &bull; Caracas, Venezuela
    </div>

</body>
</html>
