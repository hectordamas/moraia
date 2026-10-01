<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante #{{ $order->order_code }} — MORAIA</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2A2626;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #FCF7F4;
        }

        /* Outer Luxury Container */
        .page-container {
            padding: 30px 35px;
            min-height: 100%;
            background-color: #FFFFFF;
            margin: 15px;
            border: 1px solid #EAE2DE;
        }

        /* Top Brand Bar */
        .top-accent-bar {
            height: 4px;
            background-color: #C8747E;
            width: 100%;
            margin-bottom: 22px;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .logo-img {
            height: 52px;
            width: auto;
            max-width: 180px;
        }
        .brand-tagline {
            font-size: 8.5px;
            color: #947E80;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .brand-location {
            font-size: 8.5px;
            color: #7A6E6D;
            margin-top: 3px;
        }

        .header-meta-box {
            text-align: right;
            vertical-align: top;
        }
        .receipt-super-title {
            font-size: 9px;
            font-weight: bold;
            color: #C8747E;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .order-code-large {
            font-size: 14px;
            font-weight: bold;
            color: #1F1C1C;
            letter-spacing: 0.5px;
        }
        .order-date-text {
            font-size: 9px;
            color: #7A6E6D;
            margin-top: 3px;
        }

        /* Status Badge */
        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 6px;
        }
        .status-pill-pending {
            background-color: #FEF7EE;
            color: #D9822B;
            border: 1px solid #F3DCBC;
        }
        .status-pill-confirmed {
            background-color: #EBF5F0;
            color: #4E8B71;
            border: 1px solid #B8E0CD;
        }
        .status-pill-delivered {
            background-color: #EAF7EE;
            color: #2E8B57;
            border: 1px solid #B2E5C5;
        }
        .status-pill-cancelled {
            background-color: #FDF2F2;
            color: #C05C5C;
            border: 1px solid #F3BDBB;
        }

        /* Information Cards Section */
        .section-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #C8747E;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #EAE2DE;
        }

        .cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 14px 0;
            margin-bottom: 22px;
            margin-left: -14px;
            margin-right: -14px;
        }
        .info-card {
            background-color: #FAF5F2;
            border: 1px solid #EAE2DE;
            border-radius: 6px;
            padding: 12px 16px;
            vertical-align: top;
            width: 50%;
        }
        .info-card-header {
            font-size: 9px;
            font-weight: bold;
            color: #1F1C1C;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #E5D9D3;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .info-row {
            margin-bottom: 5px;
            font-size: 9.5px;
        }
        .info-label {
            font-weight: bold;
            color: #7A6E6D;
            display: inline-block;
            min-width: 65px;
        }
        .info-value {
            color: #1F1C1C;
        }

        .gift-badge {
            display: inline-block;
            background-color: #F7E4E6;
            color: #A95058;
            font-size: 8.5px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 4px;
            margin-top: 4px;
            border: 1px solid #EAC8CD;
        }

        /* Products Table */
        .items-table-wrapper {
            margin-bottom: 22px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #EAE2DE;
        }
        .items-table th {
            background-color: #C8747E;
            color: #FFFFFF;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 9px 12px;
            text-align: left;
            border: none;
        }
        .items-table th.th-center { text-align: center; }
        .items-table th.th-right { text-align: right; }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #F0E8E4;
            font-size: 9.5px;
            vertical-align: middle;
            color: #2A2626;
        }
        .items-table tr:nth-child(even) td {
            background-color: #FCF9F7;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .item-name {
            font-weight: bold;
            color: #1F1C1C;
            font-size: 10px;
        }
        .item-variant {
            font-size: 8.5px;
            color: #C8747E;
            margin-top: 2px;
            font-style: italic;
        }

        /* Bottom Section: Notes & Totals */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .bottom-table td {
            vertical-align: top;
        }
        .bottom-left-col {
            width: 55%;
            padding-right: 18px;
        }
        .bottom-right-col {
            width: 45%;
        }

        .note-box {
            background-color: #FCF7F4;
            border: 1px dashed #C8747E;
            border-radius: 6px;
            padding: 9px 13px;
            margin-bottom: 10px;
            font-size: 9px;
            color: #4A4242;
        }
        .note-box-title {
            font-weight: bold;
            color: #A95058;
            margin-bottom: 3px;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.5px;
        }

        .payment-box {
            background-color: #FAF5F2;
            border: 1px solid #EAE2DE;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 8.5px;
            color: #6B5E5D;
            line-height: 1.45;
        }
        .payment-box-title {
            font-weight: bold;
            color: #1F1C1C;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Totals Card */
        .totals-card {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #EAE2DE;
            border-radius: 6px;
            background-color: #FAF5F2;
            overflow: hidden;
        }
        .totals-card td {
            padding: 7px 14px;
            font-size: 9.5px;
        }
        .totals-card .label-cell {
            color: #7A6E6D;
        }
        .totals-card .val-cell {
            text-align: right;
            font-weight: bold;
            color: #1F1C1C;
        }
        .total-row-highlight td {
            background-color: #C8747E;
            color: #FFFFFF !important;
            font-size: 11px !important;
            font-weight: bold;
            padding: 11px 14px;
            border-top: 1px solid #C8747E;
        }
        .total-row-highlight .val-cell {
            color: #FFFFFF !important;
            font-size: 13px !important;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #EAE2DE;
            padding-top: 14px;
            margin-top: 15px;
        }
        .footer-text {
            text-align: center;
            font-size: 8.5px;
            color: #8A7B7A;
            line-height: 1.5;
        }
        .footer-brand {
            font-weight: bold;
            color: #C8747E;
            letter-spacing: 1.5px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/branding/logo_moraia_navbar_oscuro.png');
        $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
    @endphp

    <div class="page-container">
        <!-- Top Accent Color Line -->
        <div class="top-accent-bar"></div>

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" alt="MORAIA" class="logo-img">
                    @else
                        <div style="font-size: 24px; font-weight: bold; color: #C8747E; letter-spacing: 3px;">MORAIA</div>
                    @endif
                    <div class="brand-tagline">Lencería Fina &bull; Momentos Especiales</div>
                    <div class="brand-location">Caracas, Venezuela &bull; WhatsApp: +58 412 020 6548</div>
                </td>
                <td class="header-meta-box">
                    <div class="receipt-super-title">Comprobante Oficial</div>
                    <div class="order-code-large">#{{ $order->order_code }}</div>
                    <div class="order-date-text">
                        Fecha: {{ $order->created_at ? $order->created_at->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}
                    </div>
                    <div>
                        @php
                            $pillClass = match ($order->status) {
                                'Pendiente', 'Nueva' => 'status-pill-pending',
                                'Confirmada', 'Preparando', 'Lista' => 'status-pill-confirmed',
                                'Entregada' => 'status-pill-delivered',
                                'Cancelada' => 'status-pill-cancelled',
                                default => 'status-pill-pending',
                            };
                            $statusLabel = match ($order->status) {
                                'Nueva', 'Preparando' => 'PENDIENTE',
                                'Lista' => 'CONFIRMADA',
                                default => strtoupper($order->status),
                            };
                        @endphp
                        <span class="status-pill {{ $pillClass }}">
                            Estado: {{ $statusLabel }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Two Columns Information Cards -->
        <table class="cards-table">
            <tr>
                <!-- Customer Information -->
                <td class="info-card">
                    <div class="info-card-header">Datos del Cliente</div>
                    <div class="info-row">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value"><strong>{{ $order->full_name }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">WhatsApp:</span>
                        <span class="info-value">{{ $order->customer_whatsapp }}</span>
                    </div>
                    @if($order->customer_phone && $order->customer_phone !== $order->customer_whatsapp)
                        <div class="info-row">
                            <span class="info-label">Teléfono:</span>
                            <span class="info-value">{{ $order->customer_phone }}</span>
                        </div>
                    @endif
                    @if($order->customer_email)
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $order->customer_email }}</span>
                        </div>
                    @endif
                </td>

                <!-- Delivery Information -->
                <td class="info-card">
                    <div class="info-card-header">Información de Entrega</div>
                    <div class="info-row">
                        <span class="info-label">Modalidad:</span>
                        <span class="info-value"><strong>{{ $order->delivery_method_label }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ciudad:</span>
                        <span class="info-value">{{ $order->delivery_city }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Dirección:</span>
                        <span class="info-value">{{ $order->delivery_address }}</span>
                    </div>
                    @if($order->is_gift)
                        <div class="gift-badge">
                            🎁 Regalo para: {{ $order->gift_recipient_name ?? 'Destinataria Especial' }}
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 30px;" class="th-center">#</th>
                        <th>Descripción del Producto</th>
                        <th style="width: 80px;" class="th-right">Precio Unit.</th>
                        <th style="width: 50px;" class="th-center">Cant.</th>
                        <th style="width: 90px;" class="th-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalQty = 0; @endphp
                    @foreach($order->items as $index => $item)
                        @php $totalQty += $item->quantity; @endphp
                        <tr>
                            <td class="th-center" style="color: #947E80;">{{ $index + 1 }}</td>
                            <td>
                                <div class="item-name">{{ $item->product_name }}</div>
                                @if($item->variant_details)
                                    <div class="item-variant">{{ $item->variant_details }}</div>
                                @endif
                            </td>
                            <td class="th-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="th-center" style="font-weight: bold;">{{ $item->quantity }}</td>
                            <td class="th-right" style="font-weight: bold; color: #1F1C1C;">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Notes and Totals Section -->
        <table class="bottom-table">
            <tr>
                <!-- Left Notes Column -->
                <td class="bottom-left-col">
                    @if($order->is_gift && $order->gift_card_message)
                        <div class="note-box">
                            <div class="note-box-title">💌 Dedicatoria de Regalo:</div>
                            <div style="font-style: italic; color: #1F1C1C;">"{{ $order->gift_card_message }}"</div>
                        </div>
                    @endif

                    @if($order->customer_notes)
                        <div class="note-box">
                            <div class="note-box-title">📝 Notas del Pedido:</div>
                            <div style="color: #1F1C1C;">{{ $order->customer_notes }}</div>
                        </div>
                    @endif

                    <div class="payment-box">
                        <div class="payment-box-title">Atención &amp; Métodos de Pago</div>
                        Aceptamos <strong>Pago Móvil, Zelle, Transferencia Bancaria y Efectivo</strong>.<br>
                        Para coordinar y reportar tu pago, escríbenos a nuestro WhatsApp oficial: <strong>+58 412 020 6548</strong>.
                    </div>
                </td>

                <!-- Right Totals Column -->
                <td class="bottom-right-col">
                    <table class="totals-card">
                        <tr>
                            <td class="label-cell">Total Unidades:</td>
                            <td class="val-cell">{{ $totalQty }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Subtotal:</td>
                            <td class="val-cell">${{ number_format($order->subtotal, 2) }} US$</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Envío ({{ $order->delivery_method_label }}):</td>
                            <td class="val-cell">
                                @if($order->shipping_fee > 0)
                                    ${{ number_format($order->shipping_fee, 2) }} US$
                                @elseif($order->delivery_method === 'envio_nacional')
                                    Cobro en Destino
                                @else
                                    $0.00 US$
                                @endif
                            </td>
                        </tr>
                        <tr class="total-row-highlight">
                            <td>TOTAL A PAGAR:</td>
                            <td class="val-cell">${{ number_format($order->total, 2) }} US$</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer Signature -->
        <table class="footer-table">
            <tr>
                <td class="footer-text">
                    <span class="footer-brand">MORAIA</span> &bull; El arte de consentirte &bull; www.bymoraia.com<br>
                    Atención personalizada vía WhatsApp: +58 412 020 6548 &bull; Caracas, Venezuela
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
