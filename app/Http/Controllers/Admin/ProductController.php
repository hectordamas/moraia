<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\SquareImageRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images', 'variants'])->orderBy('id', 'desc');

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'search', 'catId'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'target_audience' => 'required|string|in:all,moraia,moraia_intimo',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'images.*' => ['nullable', 'image', 'max:4096', new SquareImageRule],
        ]);

        $slug = Str::slug($validated['name']);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-'.($count + 1);
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $validated['sku'] ?? 'MOR-'.strtoupper(Str::random(6)),
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'badge' => $validated['badge'] ?? null,
            'target_audience' => $validated['target_audience'],
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? $validated['short_description'],
        ]);

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $filename = 'prod_'.$product->id.'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'images/products/'.$filename,
                    'alt_text' => $product->name,
                    'sort_order' => $idx,
                    'is_cover' => $idx === 0,
                ]);
            }
        } else {
            // Default placeholder image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'images/placeholder-product.jpg',
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_cover' => true,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['images', 'allVariants']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'sku' => 'nullable|string|max:50|unique:products,sku,'.$product->id,
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'target_audience' => 'required|string|in:all,moraia,moraia_intimo',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'images.*' => ['nullable', 'image', 'max:4096', new SquareImageRule],
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?? $product->sku,
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'badge' => $validated['badge'] ?? null,
            'target_audience' => $validated['target_audience'],
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? $validated['short_description'],
        ]);

        if ($request->hasFile('images')) {
            $currentMax = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $idx => $file) {
                $filename = 'prod_'.$product->id.'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'images/products/'.$filename,
                    'alt_text' => $product->name,
                    'sort_order' => $currentMax + $idx + 1,
                    'is_cover' => false,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }
}
