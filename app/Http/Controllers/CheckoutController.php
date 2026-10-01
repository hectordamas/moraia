<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('info', 'Tu carrito está vacío.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingCaracas = (float) Setting::get('shipping_caracas_price', '3.00');

        return view('pages.checkout', compact('cart', 'subtotal', 'shippingCaracas'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tu carrito está vacío.'], 422);
            }

            return redirect()->route('shop')->with('error', 'Tu carrito está vacío.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_lastname' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'customer_whatsapp' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:150',
            'delivery_method' => 'required|string|in:delivery_caracas,envio_nacional,pickup',
            'delivery_city' => 'required|string|max:100',
            'delivery_address' => 'required|string|max:500',
            'customer_notes' => 'nullable|string|max:1000',
            'is_gift' => 'nullable|boolean',
            'gift_recipient_name' => 'nullable|string|max:120',
            'gift_card_message' => 'nullable|string|max:500',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingFee = ($validated['delivery_method'] === 'delivery_caracas')
            ? (float) Setting::get('shipping_caracas_price', '3.00')
            : 0.00;

        $total = $subtotal + $shippingFee;

        // Generate unique Order Code: MOR-YYYY-RANDOM
        $orderCode = 'MOR-'.date('Y').'-'.strtoupper(Str::random(5));

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $validated['customer_name'],
            'customer_lastname' => $validated['customer_lastname'],
            'customer_phone' => $validated['customer_phone'],
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'customer_email' => $validated['customer_email'] ?? null,
            'delivery_method' => $validated['delivery_method'],
            'delivery_city' => $validated['delivery_city'],
            'delivery_address' => $validated['delivery_address'],
            'customer_notes' => $validated['customer_notes'] ?? null,
            'is_gift' => ! empty($validated['is_gift']),
            'gift_recipient_name' => $validated['gift_recipient_name'] ?? null,
            'gift_card_message' => $validated['gift_card_message'] ?? null,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'status' => 'Nueva',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'],
                'product_image' => $item['image'] ?? null,
                'variant_details' => $item['variant_name'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Clear cart from session
        session()->forget('cart');

        $order->load('items');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'order_code' => $order->order_code,
                'customer_name' => $order->customer_name,
                'total' => number_format($order->total, 2),
                'total_formatted' => '$'.number_format($order->total, 2).' US$',
                'whatsapp_url' => $order->generateWhatsAppUrl(),
                'redirect_url' => route('order.success', ['order_code' => $order->order_code]),
            ]);
        }

        return redirect()->route('order.success', ['order_code' => $order->order_code]);
    }
}
