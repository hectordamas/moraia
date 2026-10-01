<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante #{{ $order->order_code }} — MORAIA</title>
    <style>
        @page {
            margin: 26px 30px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1A1A1A;
            font-size: 9.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }

        /* Top Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .logo-img {
            height: 38px;
            max-width: 160px;
        }
        .brand-subtext {
            font-size: 8px;
            color: #7A6E6D;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .header-meta {
            text-align: right;
            vertical-align: top;
        }
        .receipt-title {
            font-size: 11px;
            font-weight: bold;
            color: #1A1A1A;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .order-code-highlight {
            font-size: 12px;
            font-weight: bold;
            color: #1A1A1A;
            letter-spacing: 0.5px;
        }
        .order-datetime {
            font-size: 8.5px;
            color: #7A6E6D;
            margin-top: 2px;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 4px;
        }
        .badge-pending {
            background-color: #FEF7EE;
            color: #D9822B;
            border: 1px solid #F3DCBC;
        }
        .badge-confirmed {
            background-color: #EBF5F0;
            color: #4E8B71;
            border: 1px solid #B8E0CD;
        }
        .badge-delivered {
            background-color: #EAF7EE;
            color: #2E8B57;
            border: 1px solid #B2E5C5;
        }
        .badge-cancelled {
            background-color: #FDF2F2;
            color: #C05C5C;
            border: 1px solid #F3BDBB;
        }

        /* Divider Bar */
        .divider-line {
            width: 100%;
            height: 1px;
            background-color: #EAE2DE;
            margin-bottom: 14px;
        }

        /* Two Columns Cards */
        .cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 14px;
            margin-left: -8px;
            margin-right: -8px;
        }
        .card-box {
            background-color: #FAF5F2;
            border: 1px solid #EAE2DE;
            border-radius: 4px;
            padding: 9px 12px;
            vertical-align: top;
            width: 50%;
        }
        .card-header-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #1A1A1A;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #E4D8D2;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .card-line {
            margin-bottom: 3px;
            font-size: 9px;
            color: #2A2626;
        }
        .card-label {
            font-weight: bold;
            color: #7A6E6D;
            display: inline-block;
            min-width: 60px;
        }

        /* Gift Special Box */
        .gift-tag {
            display: inline-block;
            background-color: #F7E4E6;
            color: #A95058;
            font-size: 8px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
            margin-top: 3px;
        }

        /* Products Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            border: 1px solid #EAE2DE;
        }
        .items-table th {
            background-color: #F4ECE8;
            color: #1A1A1A;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 7px 8px;
            text-align: left;
            border-bottom: 1px solid #EAE2DE;
        }
        .items-table th.th-center { text-align: center; }
        .items-table th.th-right { text-align: right; }
        .items-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #F0E8E4;
            font-size: 9px;
            vertical-align: middle;
            color: #2A2626;
        }
        .items-table tr:nth-child(even) td {
            background-color: #FCFAF8;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .product-title {
            font-weight: bold;
            color: #1A1A1A;
            font-size: 9.5px;
        }
        .product-variant-text {
            font-size: 8px;
            color: #7A6E6D;
            margin-top: 2px;
            font-style: italic;
        }

        /* Bottom Section: Notes & Totals */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .bottom-table td {
            vertical-align: top;
        }
        .left-notes-col {
            width: 55%;
            padding-right: 12px;
        }
        .right-totals-col {
            width: 45%;
        }

        .note-card {
            background-color: #FAF5F2;
            border: 1px dashed #D4C7C2;
            border-radius: 4px;
            padding: 7px 10px;
            margin-bottom: 6px;
            font-size: 8.5px;
            color: #4A4242;
        }
        .note-card-title {
            font-weight: bold;
            color: #1A1A1A;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }

        .payment-info-box {
            background-color: #FAF5F2;
            border: 1px solid #EAE2DE;
            border-radius: 4px;
            padding: 7px 10px;
            font-size: 8px;
            color: #6B5E5D;
            line-height: 1.35;
        }
        .payment-info-title {
            font-weight: bold;
            color: #1A1A1A;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Totals Card */
        .totals-card {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #EAE2DE;
            border-radius: 4px;
            background-color: #FAF5F2;
        }
        .totals-card td {
            padding: 5px 10px;
            font-size: 9px;
        }
        .totals-card .label-col {
            color: #6B5E5D;
        }
        .totals-card .val-col {
            text-align: right;
            font-weight: bold;
            color: #1A1A1A;
        }
        .total-highlight-row td {
            background-color: #1A1A1A;
            color: #FFFFFF !important;
            font-size: 10.5px !important;
            font-weight: bold;
            padding: 8px 10px;
            border-top: 1px solid #1A1A1A;
        }
        .total-highlight-row .val-col {
            color: #FFFFFF !important;
            font-size: 11px !important;
        }

        /* Footer */
        .footer-wrap {
            border-top: 1px solid #EAE2DE;
            padding-top: 10px;
            text-align: center;
            font-size: 8px;
            color: #8A7B7A;
            margin-top: 16px;
            line-height: 1.4;
        }
        .footer-brand-name {
            font-weight: bold;
            color: #1A1A1A;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/branding/logo_moraia_navbar_oscuro.png');
        $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
    @endphp

    <!-- Brand Header -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="MORAIA" class="logo-img">
                @else
                    <div style="font-size: 20px; font-weight: bold; color: #1A1A1A; letter-spacing: 2px;">MORAIA</div>
                @endif
                <div class="brand-subtext">Lencería Fina &bull; Detalles Exclusivos</div>
                <div style="font-size: 8px; color: #7A6E6D; margin-top: 2px;">
                    Caracas, Venezuela &bull; WhatsApp: +58 412 020 6548
                </div>
            </td>
            <td class="header-meta">
                <div class="receipt-title">Comprobante de Pedido</div>
                <div class="order-code-highlight">#{{ $order->order_code }}</div>
                <div class="order-datetime">
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
                        $statusLabel = match ($order->status) {
                            'Nueva', 'Preparando' => 'PENDIENTE',
                            'Lista' => 'CONFIRMADA',
                            default => strtoupper($order->status),
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        Estado: {{ $statusLabel }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Subtle Divider Line -->
    <div class="divider-line"></div>

    <!-- Customer & Delivery Two Columns -->
    <table class="cards-table">
        <tr>
            <!-- Customer Card -->
            <td class="card-box">
                <div class="card-header-title">Datos del Cliente</div>
                <div class="card-line">
                    <span class="card-label">Nombre:</span>
                    <strong>{{ $order->full_name }}</strong>
                </div>
                <div class="card-line">
                    <span class="card-label">WhatsApp:</span>
                    {{ $order->customer_whatsapp }}
                </div>
                @if($order->customer_phone && $order->customer_phone !== $order->customer_whatsapp)
                    <div class="card-line">
                        <span class="card-label">Teléfono:</span>
                        {{ $order->customer_phone }}
                    </div>
                @endif
                @if($order->customer_email)
                    <div class="card-line">
                        <span class="card-label">Email:</span>
                        {{ $order->customer_email }}
                    </div>
                @endif
            </td>

            <!-- Delivery Card -->
            <td class="card-box">
                <div class="card-header-title">Información de Entrega</div>
                <div class="card-line">
                    <span class="card-label">Modalidad:</span>
                    <strong>{{ $order->delivery_method_label }}</strong>
                </div>
                <div class="card-line">
                    <span class="card-label">Ciudad:</span>
                    {{ $order->delivery_city }}
                </div>
                <div class="card-line">
                    <span class="card-label">Dirección:</span>
                    {{ $order->delivery_address }}
                </div>
                @if($order->is_gift)
                    <div class="gift-tag">
                        🎁 Regalo para: {{ $order->gift_recipient_name ?? 'Destinataria' }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Products Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="th-center">#</th>
                <th>Descripción del Producto</th>
                <th style="width: 75px;" class="th-right">Precio Unit.</th>
                <th style="width: 45px;" class="th-center">Cant.</th>
                <th style="width: 80px;" class="th-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalQty = 0; @endphp
            @foreach($order->items as $index => $item)
                @php $totalQty += $item->quantity; @endphp
                <tr>
                    <td class="th-center" style="color: #8A7B7A;">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-title">{{ $item->product_name }}</div>
                        @if($item->variant_details)
                            <div class="product-variant-text">{{ $item->variant_details }}</div>
                        @endif
                    </td>
                    <td class="th-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="th-center" style="font-weight: bold;">{{ $item->quantity }}</td>
                    <td class="th-right" style="font-weight: bold;">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bottom Section: Notes & Totals -->
    <table class="bottom-table">
        <tr>
            <!-- Left Notes Column -->
            <td class="left-notes-col">
                @if($order->is_gift && $order->gift_card_message)
                    <div class="note-card">
                        <div class="note-card-title">💌 Dedicatoria de Regalo:</div>
                        <div style="font-style: italic;">"{{ $order->gift_card_message }}"</div>
                    </div>
                @endif

                @if($order->customer_notes)
                    <div class="note-card">
                        <div class="note-card-title">📝 Notas del Pedido:</div>
                        <div>{{ $order->customer_notes }}</div>
                    </div>
                @endif

                <div class="payment-info-box">
                    <div class="payment-info-title">Atención y Métodos de Pago</div>
                    Aceptamos <strong>Pago Móvil, Zelle, Transferencia Bancaria y Efectivo</strong>. Para coordinar y confirmar tu comprobante de pago, contáctanos a nuestro WhatsApp oficial: <strong>+58 412 020 6548</strong>.
                </div>
            </td>

            <!-- Right Totals Column -->
            <td class="right-totals-col">
                <table class="totals-card">
                    <tr>
                        <td class="label-col">Total Unidades:</td>
                        <td class="val-col">{{ $totalQty }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Subtotal:</td>
                        <td class="val-col">${{ number_format($order->subtotal, 2) }} US$</td>
                    </tr>
                    <tr>
                        <td class="label-col">Envío ({{ $order->delivery_method_label }}):</td>
                        <td class="val-col">
                            @if($order->shipping_fee > 0)
                                ${{ number_format($order->shipping_fee, 2) }} US$
                            @elseif($order->delivery_method === 'envio_nacional')
                                Cobro en Destino
                            @else
                                $0.00 US$
                            @endif
                        </td>
                    </tr>
                    <tr class="total-highlight-row">
                        <td>TOTAL A PAGAR:</td>
                        <td class="val-col">${{ number_format($order->total, 2) }} US$</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer-wrap">
        <span class="footer-brand-name">MORAIA</span> &bull; El arte de consentirte &bull; Belleza, Lencería Fina &amp; Momentos Especiales<br>
        Caracas, Venezuela &bull; www.bymoraia.com &bull; WhatsApp Oficial: +58 412 020 6548
    </div>

</body>
</html>
