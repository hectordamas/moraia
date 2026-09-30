<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderSuccessController extends Controller
{
    public function show(string $order_code): View
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();
        $whatsAppUrl = $order->generateWhatsAppUrl();

        return view('pages.order-success', compact('order', 'whatsAppUrl'));
    }
}
