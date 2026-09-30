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
        $phone = '584120206548';

        $msg = "🌸 *¡Hola Moraia! Acabo de registrar mi pedido en la web:*\n\n";
        $msg .= "📋 *Orden:* #{$this->order_code}\n";
        $msg .= "👤 *Cliente:* {$this->full_name}\n";
        $msg .= "📱 *WhatsApp:* {$this->customer_whatsapp}\n";
        $msg .= "📍 *Entrega:* {$this->delivery_method_label} - {$this->delivery_city}\n";
        $msg .= "🏠 *Dirección:* {$this->delivery_address}\n\n";

        if ($this->is_gift) {
            $msg .= "🎁 *Es un Regalo:* Sí\n";
            if ($this->gift_recipient_name) {
                $msg .= "💝 *Para:* {$this->gift_recipient_name}\n";
            }
            if ($this->gift_card_message) {
                $msg .= "💌 *Mensaje de Tarjeta:* \"{$this->gift_card_message}\"\n";
            }
            $msg .= "\n";
        }

        $msg .= "🛍️ *Detalle del Pedido:*\n";
        foreach ($this->items as $item) {
            $variant = $item->variant_details ? " ({$item->variant_details})" : '';
            $msg .= "• {$item->quantity}x {$item->product_name}{$variant} - $".number_format($item->total_price, 2)."\n";
        }

        $msg .= "\n💰 *Subtotal:* $".number_format($this->subtotal, 2)."\n";
        if ($this->shipping_fee > 0) {
            $msg .= '🚚 *Envío:* $'.number_format($this->shipping_fee, 2)."\n";
        }
        $msg .= '✨ *Total:* $'.number_format($this->total, 2)."\n\n";

        if ($this->customer_notes) {
            $msg .= "📝 *Notas adicionales:* {$this->customer_notes}\n\n";
        }

        $msg .= '¿Podrían indicarme los métodos de pago disponibles para concretar mi orden? ¡Muchas gracias! 💕';

        return "https://wa.me/{$phone}?text=".rawurlencode($msg);
    }

    public function generateAdminWhatsAppUrl(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->customer_whatsapp);
        if (! str_starts_with($cleanPhone, '58') && strlen($cleanPhone) === 10) {
            $cleanPhone = '58'.$cleanPhone;
        }

        $msg = "Hola {$this->customer_name}, te escribimos de *MORAIA* 💕\n";
        $msg .= "Estamos procesando tu pedido *#{$this->order_code}*.\n";
        $msg .= 'Total a pagar: $'.number_format($this->total, 2)."\n\n";
        $msg .= '¿Deseas coordinar el pago y los detalles de tu entrega?';

        return "https://wa.me/{$cleanPhone}?text=".rawurlencode($msg);
    }
}
