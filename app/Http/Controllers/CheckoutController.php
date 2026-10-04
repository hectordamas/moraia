<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Packaging;
use App\Models\Product;
use App\Models\ProductVariant;
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
        $packagings = Packaging::active()->get();

        return view('pages.checkout', compact('cart', 'subtotal', 'shippingCaracas', 'packagings'));
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
            'packaging_id' => 'nullable|exists:packagings,id',
            'is_gift' => 'nullable|boolean',
            'gift_recipient_name' => 'nullable|string|max:120',
            'gift_card_message' => 'nullable|string|max:500',
        ]);

        // Pre-validate live stock before processing order
        foreach ($cart as $item) {
            $availableStock = 0;
            if (! empty($item['variant_id'])) {
                $var = ProductVariant::find($item['variant_id']);
                $availableStock = $var ? (int) $var->stock_quantity : 0;
            } elseif (! empty($item['product_id'])) {
                $prod = Product::find($item['product_id']);
                $availableStock = $prod ? (int) $prod->stock_quantity : 0;
            }

            if ($item['quantity'] > $availableStock) {
                $itemLabel = $item['name'].(! empty($item['variant_name']) ? " ({$item['variant_name']})" : '');
                $msg = $availableStock > 0
                    ? "Lo sentimos, solo quedan {$availableStock} unidades disponibles de '{$itemLabel}'. Por favor ajusta tu bolsa."
                    : "El producto '{$itemLabel}' se ha agotado. Por favor retíralo de tu bolsa para continuar.";

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->route('cart')->with('error', $msg);
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingFee = ($validated['delivery_method'] === 'delivery_caracas')
            ? (float) Setting::get('shipping_caracas_price', '3.00')
            : 0.00;

        $packaging = null;
        if (! empty($validated['packaging_id'])) {
            $packaging = Packaging::find($validated['packaging_id']);
        } else {
            $packaging = Packaging::where('is_default', true)->where('is_active', true)->first();
        }

        $packagingPrice = $packaging ? (float) $packaging->price : 0.00;
        $packagingName = $packaging ? $packaging->name : null;

        $total = $subtotal + $shippingFee + $packagingPrice;

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
            'packaging_id' => $packaging?->id,
            'packaging_name' => $packagingName,
            'packaging_price' => $packagingPrice,
            'is_gift' => ! empty($validated['is_gift']),
            'gift_recipient_name' => $validated['gift_recipient_name'] ?? null,
            'gift_card_message' => $validated['gift_card_message'] ?? null,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'status' => 'Pendiente',
        ]);

        foreach ($cart as $item) {
            $details = $item['variant_name'] ?? null;
            if (! empty($item['customization_text'])) {
                $details = $details ? "{$details} • {$item['customization_text']}" : $item['customization_text'];
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'variant_id' => $item['variant_id'] ?? null,
                'product_name' => $item['name'],
                'product_image' => $item['image'] ?? null,
                'variant_details' => $details,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Decrement stock for all items
        $order->load('items');
        $order->decrementStock();

        // Clear cart from session
        session()->forget('cart');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'order_code' => $order->order_code,
                'customer_name' => $order->customer_name,
                'packaging_name' => $order->packaging_name,
                'total' => number_format($order->total, 2),
                'total_formatted' => '$'.number_format($order->total, 2).' US$',
                'whatsapp_url' => $order->generateWhatsAppUrl(),
                'pdf_url' => route('order.pdf', ['order_code' => $order->order_code]),
                'redirect_url' => route('order.success', ['order_code' => $order->order_code]),
            ]);
        }

        return redirect()->route('order.success', ['order_code' => $order->order_code]);
    }
}
