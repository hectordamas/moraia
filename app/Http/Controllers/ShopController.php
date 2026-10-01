<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        $query = Product::with(['images', 'category', 'variants'])
            ->where('is_active', true);

        // Search query
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQ) use ($search) {
                        $catQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Category Filter
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Audience Line Filter (e.g. 'moraia_intimo')
        if ($line = $request->input('line')) {
            if ($line === 'intimo') {
                $query->where('target_audience', 'moraia_intimo');
            } elseif ($line === 'moraia') {
                $query->where('target_audience', '!=', 'moraia_intimo');
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->paginate(12)->withQueryString();

        $activeCategory = $categorySlug
            ? Category::where('slug', $categorySlug)->first()
            : null;

        return view('pages.shop', compact(
            'products',
            'categories',
            'activeCategory',
            'search',
            'sort',
            'line'
        ));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        $products = Product::with(['images', 'category', 'variants'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('pages.category', compact(
            'category',
            'categories',
            'products'
        ));
    }

    public function product(string $slug): View
    {
        $product = Product::with(['images', 'category', 'variants'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['images', 'category', 'variants'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->when($product->category_id, function ($query) use ($product) {
                $query->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$product->category_id]);
            })
            ->latest()
            ->take(8)
            ->get();

        return view('pages.product', compact('product', 'relatedProducts'));
    }
}
