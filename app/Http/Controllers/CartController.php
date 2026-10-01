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
        ]);

        $product = Product::with(['images', 'variants'])->findOrFail($request->input('product_id'));

        $activeVariants = $product->variants->where('is_active', true);
        if ($activeVariants->isNotEmpty() && ! $request->input('variant_id')) {
            return response()->json([
                'success' => false,
                'requires_variant' => true,
                'redirect_url' => route('product', $product->slug),
                'message' => 'Por favor selecciona una talla o color para este producto.',
            ], 422);
        }

        $variant = $request->input('variant_id')
            ? ProductVariant::where('is_active', true)->find($request->input('variant_id'))
            : null;

        $quantity = (int) ($request->input('quantity', 1));
        $cartKey = $variant ? "{$product->id}_{$variant->id}" : "{$product->id}_0";

        $price = $product->price + ($variant ? $variant->price_modifier : 0);
        $cart = session()->get('cart', []);

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
                'variant_name' => $variant ? "{$variant->variant_type}: {$variant->name}" : null,
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

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $delta;
            if ($cart[$cartKey]['quantity'] <= 0) {
                unset($cart[$cartKey]);
            }
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
