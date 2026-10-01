<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_lastname',
        'customer_phone',
        'customer_whatsapp',
        'customer_email',
        'delivery_method',
        'delivery_city',
        'delivery_address',
        'customer_notes',
        'is_gift',
        'gift_recipient_name',
        'gift_card_message',
        'subtotal',
        'shipping_fee',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_gift' => 'boolean',
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->customer_name} {$this->customer_lastname}";
    }

    public function getDeliveryMethodLabelAttribute(): string
    {
        return match ($this->delivery_method) {
            'delivery_caracas' => 'Delivery propio en Caracas',
            'envio_nacional' => 'Envío Nacional (MRW/Zoom/Tealca)',
            'pickup' => 'Pick-up / Retiro acordado',
            default => ucfirst(str_replace('_', ' ', $this->delivery_method)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Nueva' => 'badge-new',
            'Confirmada' => 'badge-confirmed',
            'Preparando' => 'badge-preparing',
            'Lista' => 'badge-ready',
            'Entregada' => 'badge-delivered',
            'Cancelada' => 'badge-cancelled',
            default => 'badge-default',
        };
    }

    public function generateWhatsAppUrl(): string
    {
        $phone = Setting::get('contact_whatsapp_clean', '584120206548');
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        $lines = [];
        $lines[] = '*NUEVO PEDIDO — MORAIA*';
        $lines[] = '━━━━━━━━━━━━━━━━━━━━';
        $lines[] = "• *Orden:* #{$this->order_code}";
        if ($this->created_at) {
            $lines[] = "• *Fecha:* {$this->created_at->format('d/m/Y h:i A')}";
        }

        $lines[] = '';
        $lines[] = '*DATOS DEL CLIENTE*';
        $lines[] = "• *Nombre:* {$this->full_name}";
        $lines[] = "• *Teléfono / WhatsApp:* {$this->customer_whatsapp}";
        if ($this->customer_phone && $this->customer_phone !== $this->customer_whatsapp) {
            $lines[] = "• *Teléfono Alt.:* {$this->customer_phone}";
        }
        if ($this->customer_email) {
            $lines[] = "• *Email:* {$this->customer_email}";
        }

        $lines[] = '';
        $lines[] = '*INFORMACIÓN DE ENTREGA*';
        $lines[] = "• *Método:* {$this->delivery_method_label}";
        $lines[] = "• *Ciudad:* {$this->delivery_city}";
        $lines[] = "• *Dirección:* {$this->delivery_address}";

        if ($this->is_gift) {
            $lines[] = '';
            $lines[] = '*DETALLES DEL REGALO*';
            if ($this->gift_recipient_name) {
                $lines[] = "• *Para:* {$this->gift_recipient_name}";
            }
            if ($this->gift_card_message) {
                $lines[] = "• *Mensaje en tarjeta:* \"{$this->gift_card_message}\"";
            }
        }

        $lines[] = '';
        $lines[] = '*DETALLE DE PRODUCTOS*';
        $lines[] = '━━━━━━━━━━━━━━━━━━━━';

        $totalUnits = 0;
        $itemsCount = count($items);
        foreach ($items as $index => $item) {
            $variant = $item->variant_details ? " ({$item->variant_details})" : '';
            $unitPrice = number_format($item->unit_price, 2);
            $itemTotal = number_format($item->total_price, 2);
            $totalUnits += $item->quantity;

            $lines[] = "*{$item->product_name}*{$variant}";
            $lines[] = "   └ {$item->quantity} unid. × \${$unitPrice} = *{$itemTotal} US\$*";

            if ($index < $itemsCount - 1) {
                $lines[] = '────────────────────';
            }
        }

        $lines[] = '━━━━━━━━━━━━━━━━━━━━';
        $lines[] = '*RESUMEN DE LA ORDEN*';
        $lines[] = "• *Total Unidades:* {$totalUnits}";
        $lines[] = '• *Subtotal:* $'.number_format($this->subtotal, 2).' US$';

        if ($this->shipping_fee > 0) {
            $lines[] = '• *Envío:* $'.number_format($this->shipping_fee, 2).' US$';
        } elseif ($this->delivery_method === 'envio_nacional') {
            $lines[] = '• *Envío:* Cobro en Destino';
        } else {
            $lines[] = '• *Envío:* Gratis / Retiro';
        }

        $lines[] = '• *TOTAL A PAGAR:* *'.number_format($this->total, 2).' US$*';
        $lines[] = '━━━━━━━━━━━━━━━━━━━━';

        if ($this->customer_notes) {
            $lines[] = '';
            $lines[] = '*Notas adicionales:*';
            $lines[] = "_{$this->customer_notes}_";
        }

        $lines[] = '';
        $lines[] = '_¡Hola! Acabo de registrar mi pedido en la web. ¿Podrían confirmarme la disponibilidad y los datos de pago para concretar la compra? ¡Muchas gracias!_';

        $msg = implode("\n", $lines);

        return "https://wa.me/{$phone}?text=".rawurlencode($msg);
    }

    public function generateAdminWhatsAppUrl(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->customer_whatsapp);
        if (! str_starts_with($cleanPhone, '58') && strlen($cleanPhone) === 10) {
            $cleanPhone = '58'.$cleanPhone;
        }

        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();

        $lines = [];
        $lines[] = '*MORAIA BOUTIQUE*';
        $lines[] = '━━━━━━━━━━━━━━━━━━━━';
        $lines[] = "¡Hola *{$this->customer_name}*, un gusto saludarte!";
        $lines[] = "Te escribimos para coordinar tu orden *#{$this->order_code}*.";
        $lines[] = '';
        $lines[] = '*Resumen del Pedido:*';

        foreach ($items as $item) {
            $variant = $item->variant_details ? " ({$item->variant_details})" : '';
            $lines[] = "• {$item->quantity}x {$item->product_name}{$variant} - $".number_format($item->total_price, 2).' US$';
        }

        $lines[] = '';
        $lines[] = '• *Total a pagar:* $'.number_format($this->total, 2).' US$';
        $lines[] = "• *Modalidad:* {$this->delivery_method_label} ({$this->delivery_city})";
        $lines[] = '━━━━━━━━━━━━━━━━━━━━';
        $lines[] = '¿Deseas que te compartamos los datos de pago (Pago Móvil, Zelle, Efectivo) para procesar tu entrega?';

        $msg = implode("\n", $lines);

        return "https://wa.me/{$cleanPhone}?text=".rawurlencode($msg);
    }
}
