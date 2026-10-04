<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Packaging;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['images', 'variants', 'category'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $packagings = Packaging::active()->get();

        $caracasShippingFee = (float) Setting::get('shipping_caracas_price', '3.00');

        return view('admin.pos.index', compact('products', 'categories', 'packagings', 'caracasShippingFee'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_lastname' => 'required|string|max:100',
            'customer_whatsapp' => 'required|string|max:30',
            'customer_phone' => 'nullable|string|max:30',
            'customer_email' => 'nullable|email|max:120',
            'delivery_method' => 'required|string|in:delivery_caracas,envio_nacional,pickup,venta_directa',
            'delivery_city' => 'required|string|max:100',
            'delivery_address' => 'required|string|max:500',
            'customer_notes' => 'nullable|string|max:1000',
            'packaging_id' => 'nullable|exists:packagings,id',
            'is_gift' => 'nullable|boolean',
            'gift_recipient_name' => 'nullable|string|max:120',
            'gift_card_message' => 'nullable|string|max:500',
            'status' => 'required|string|in:Pendiente,Confirmada,Entregada',
            'shipping_fee' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable',
            'items.*.variant_details' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $itemsData = [];

        foreach ($validated['items'] as $itemInput) {
            $product = Product::with('images')->findOrFail($itemInput['product_id']);
            $variant = ! empty($itemInput['variant_id'])
                ? ProductVariant::find($itemInput['variant_id'])
                : null;

            $qty = (int) $itemInput['quantity'];
            $unitPrice = (float) $itemInput['unit_price'];
            $itemTotal = $unitPrice * $qty;
            $subtotal += $itemTotal;

            $variantDetails = $itemInput['variant_details'] ?? null;
            if (! $variantDetails && $variant) {
                $variantDetails = ucfirst($variant->variant_type).': '.$variant->name;
            }

            $itemsData[] = [
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'product_name' => $product->name,
                'product_image' => $product->cover_image_url,
                'variant_details' => $variantDetails,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $itemTotal,
            ];
        }

        $discount = (float) ($validated['discount_amount'] ?? 0);
        $shippingFee = (float) $validated['shipping_fee'];

        $packaging = null;
        if (! empty($validated['packaging_id'])) {
            $packaging = Packaging::find($validated['packaging_id']);
        } else {
            $packaging = Packaging::where('is_default', true)->where('is_active', true)->first();
        }

        $packagingPrice = $packaging ? (float) $packaging->price : 0.00;
        $packagingName = $packaging ? $packaging->name : null;

        $total = max(0, ($subtotal - $discount) + $shippingFee + $packagingPrice);

        // Generate unique Order Code: MOR-YYYY-RANDOM
        $orderCode = 'MOR-'.date('Y').'-'.strtoupper(Str::random(5));

        $notes = $validated['customer_notes'] ?? '';
        if ($discount > 0) {
            $notes = trim($notes.' [Descuento POS aplicado: -$'.number_format($discount, 2).']');
        }

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $validated['customer_name'],
            'customer_lastname' => $validated['customer_lastname'],
            'customer_phone' => $validated['customer_phone'] ?? $validated['customer_whatsapp'],
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'customer_email' => $validated['customer_email'] ?? null,
            'delivery_method' => $validated['delivery_method'],
            'delivery_city' => $validated['delivery_city'],
            'delivery_address' => $validated['delivery_address'],
            'customer_notes' => $notes ?: null,
            'packaging_id' => $packaging?->id,
            'packaging_name' => $packagingName,
            'packaging_price' => $packagingPrice,
            'is_gift' => ! empty($validated['is_gift']),
            'gift_recipient_name' => $validated['gift_recipient_name'] ?? null,
            'gift_card_message' => $validated['gift_card_message'] ?? null,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'status' => $validated['status'],
        ]);

        foreach ($itemsData as $item) {
            $item['order_id'] = $order->id;
            OrderItem::create($item);
        }

        // Deduct inventory
        $order->load('items');
        if ($order->status !== 'Cancelada') {
            $order->decrementStock();
        }

        return response()->json([
            'success' => true,
            'message' => "¡Orden #{$order->order_code} creada con éxito!",
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'total_formatted' => '$'.number_format($order->total, 2),
            'customer_name' => $order->full_name,
            'whatsapp_url' => $order->generateAdminWhatsAppUrl(),
            'pdf_url' => route('admin.orders.pdf', $order->id),
            'show_url' => route('admin.orders.show', $order->id),
        ]);
    }
}
