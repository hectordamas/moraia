<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $totals = $this->calculateTotals($cart);

        return view('pages.cart', [
            'cart' => $cart,
            'subtotal' => $totals['subtotal'],
            'total' => $totals['total'],
            'itemCount' => $totals['count'],
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:50',
            'customizations' => 'nullable|array',
            'customizations.*.group' => 'nullable|string|max:150',
            'customizations.*.label' => 'required|string|max:150',
            'customizations.*.price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::with(['images', 'variants'])->findOrFail($request->input('product_id'));

        $activeVariants = $product->variants->where('is_active', true);
        if ($activeVariants->isNotEmpty() && ! $request->input('variant_id')) {
            return response()->json([
                'success' => false,
                'requires_variant' => true,
                'redirect_url' => route('product', $product->slug),
                'message' => 'Por favor selecciona las opciones requeridas (talla o color) para este producto.',
            ], 422);
        }

        $variant = $request->input('variant_id')
            ? ProductVariant::where('is_active', true)->find($request->input('variant_id'))
            : null;

        // Stock Verification
        $availableStock = $variant ? (int) $variant->stock_quantity : (int) $product->stock_quantity;

        if ($availableStock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, este producto o combinación se encuentra actualmente agotado.',
            ], 422);
        }

        $quantity = (int) ($request->input('quantity', 1));

        // Process Customizations & Extra Add-on Costs
        $customizations = $request->input('customizations', []);
        $customExtra = 0;
        $customizationLabels = [];

        if (is_array($customizations)) {
            foreach ($customizations as $c) {
                $p = ! empty($c['price']) ? (float) $c['price'] : 0;
                $customExtra += $p;
                $labelStr = $c['label'];
                if ($p > 0) {
                    $labelStr .= ' (+$'.number_format($p, 2).')';
                }
                $customizationLabels[] = ! empty($c['group']) ? "{$c['group']}: {$labelStr}" : $labelStr;
            }
        }

        $price = (float) $product->price + ($variant ? (float) $variant->price_modifier : 0) + $customExtra;

        // Unique cart key considering variant + customizations
        $customHash = ! empty($customizations) ? '_'.substr(md5(json_encode($customizations)), 0, 8) : '';
        $cartKey = $variant ? "{$product->id}_{$variant->id}{$customHash}" : "{$product->id}_0{$customHash}";
        $cart = session()->get('cart', []);

        $currentInCart = isset($cart[$cartKey]) ? (int) $cart[$cartKey]['quantity'] : 0;

        if (($currentInCart + $quantity) > $availableStock) {
            $remaining = max(0, $availableStock - $currentInCart);
            $message = $remaining > 0
                ? "Solo puedes agregar {$remaining} unidad(es) más. Ya tienes {$currentInCart} en tu bolsa (Stock disponible: {$availableStock})."
                : "Has alcanzado el límite de stock disponible ({$availableStock} unidades) para este producto en tu bolsa.";

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->cover_image_url,
                'price' => (float) $price,
                'variant_name' => $variant ? ($variant->variant_type === 'combinacion' ? $variant->name : "{$variant->variant_type}: {$variant->name}") : null,
                'customizations' => $customizations,
                'customization_text' => ! empty($customizationLabels) ? implode(' • ', $customizationLabels) : null,
                'max_stock' => $availableStock,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cartCount' => $totals['count'],
            'subtotal' => $totals['subtotal'],
            'subtotalFormatted' => '$'.number_format($totals['subtotal'], 2),
            'cartHtml' => view('partials.cart-drawer-items', ['cart' => $cart])->render(),
            'pageCartHtml' => view('partials.cart-page-items', ['cart' => $cart])->render(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'cart_key' => 'required|string',
            'delta' => 'required|integer',
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->input('cart_key');
        $delta = (int) $request->input('delta');

        if (! isset($cart[$cartKey])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito.',
            ], 404);
        }

        if ($delta > 0) {
            $item = $cart[$cartKey];
            $availableStock = 9999;

            if (! empty($item['variant_id'])) {
                $var = ProductVariant::find($item['variant_id']);
                if ($var) {
                    $availableStock = (int) $var->stock_quantity;
                }
            } elseif (! empty($item['product_id'])) {
                $prod = Product::find($item['product_id']);
                if ($prod) {
                    $availableStock = (int) $prod->stock_quantity;
                }
            }

            if (($item['quantity'] + $delta) > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock máximo alcanzado ({$availableStock} unidades disponibles).",
                ], 422);
            }
        }

        $cart[$cartKey]['quantity'] += $delta;
        if ($cart[$cartKey]['quantity'] <= 0) {
            unset($cart[$cartKey]);
        }
        session()->put('cart', $cart);

        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cartCount' => $totals['count'],
            'subtotal' => $totals['subtotal'],
            'subtotalFormatted' => '$'.number_format($totals['subtotal'], 2),
            'cartHtml' => view('partials.cart-drawer-items', ['cart' => $cart])->render(),
            'pageCartHtml' => view('partials.cart-page-items', ['cart' => $cart])->render(),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->input('cart_key');

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        $totals = $this->calculateTotals($cart);

        return response()->json([
            'success' => true,
            'cartCount' => $totals['count'],
            'subtotal' => $totals['subtotal'],
            'subtotalFormatted' => '$'.number_format($totals['subtotal'], 2),
            'cartHtml' => view('partials.cart-drawer-items', ['cart' => $cart])->render(),
            'pageCartHtml' => view('partials.cart-page-items', ['cart' => $cart])->render(),
        ]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'cartCount' => 0,
            'subtotal' => 0,
            'subtotalFormatted' => '$0.00',
            'cartHtml' => view('partials.cart-drawer-items', ['cart' => []])->render(),
            'pageCartHtml' => view('partials.cart-page-items', ['cart' => []])->render(),
        ]);
    }

    private function calculateTotals(array $cart): array
    {
        $subtotal = 0;
        $count = 0;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            $count += $item['quantity'];
        }

        return [
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'count' => $count,
        ];
    }
}
