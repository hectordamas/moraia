<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    public function generateWhatsAppReplyUrl(): string
    {
        if (! $this->phone) {
            return '';
        }
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
        if (! str_starts_with($cleanPhone, '58') && strlen($cleanPhone) === 10) {
            $cleanPhone = '58'.$cleanPhone;
        }

        $msg = "Hola {$this->name}, gracias por escribirnos a *MORAIA* 💕\n";
        $msg .= "Con respecto a tu consulta: \"{$this->subject}\"\n\n";
        $msg .= '¿En qué podemos ayudarte hoy?';

        return "https://wa.me/{$cleanPhone}?text=".rawurlencode($msg);
    }
}
