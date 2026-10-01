<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
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
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.variant_type' => 'nullable|string|max:50',
            'variants.*.value' => 'nullable|string|max:100',
            'variants.*.price_modifier' => 'nullable|numeric',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
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
            'seo_description' => $validated['seo_description'] ?? ($validated['short_description'] ?? null),
        ]);

        // Handle Image Uploads to public/uploads/products
        $uploadDir = public_path('uploads/products');
        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');

            // Check if client provided custom order indexes
            $orderData = $request->input('image_order');
            if ($orderData) {
                $orderIndexes = is_string($orderData) ? json_decode($orderData, true) : $orderData;
                if (is_array($orderIndexes) && ! empty($orderIndexes)) {
                    $sortedFiles = [];
                    foreach ($orderIndexes as $idxKey) {
                        $cleanIdx = is_numeric($idxKey) ? (int) $idxKey : (int) str_replace('new_', '', $idxKey);
                        if (isset($uploadedFiles[$cleanIdx])) {
                            $sortedFiles[] = $uploadedFiles[$cleanIdx];
                            unset($uploadedFiles[$cleanIdx]);
                        }
                    }
                    foreach ($uploadedFiles as $remaining) {
                        $sortedFiles[] = $remaining;
                    }
                    $uploadedFiles = $sortedFiles;
                }
            }

            foreach ($uploadedFiles as $idx => $file) {
                $filename = 'prod_'.$product->id.'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'uploads/products/'.$filename,
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

        // Handle Variants (Tallas & Colores)
        if ($request->has('variants') && is_array($request->input('variants'))) {
            foreach ($request->input('variants') as $vData) {
                if (! empty($vData['name'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_type' => $vData['variant_type'] ?? 'talla',
                        'name' => $vData['name'],
                        'value' => $vData['value'] ?? $vData['name'],
                        'price_modifier' => ! empty($vData['price_modifier']) ? (float) $vData['price_modifier'] : 0.00,
                        'stock_quantity' => ! empty($vData['stock_quantity']) ? (int) $vData['stock_quantity'] : 10,
                        'is_active' => isset($vData['is_active']) ? (bool) $vData['is_active'] : true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente con sus imágenes y variantes.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $product->load([
            'images' => fn ($q) => $q->orderBy('sort_order', 'asc'),
            'allVariants',
        ]);

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
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.variant_type' => 'nullable|string|max:50',
            'variants.*.value' => 'nullable|string|max:100',
            'variants.*.price_modifier' => 'nullable|numeric',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
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
            'seo_description' => $validated['seo_description'] ?? ($validated['short_description'] ?? null),
        ]);

        $uploadDir = public_path('uploads/products');
        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 1. Delete marked images
        if ($request->filled('deleted_image_ids')) {
            $deletedIds = $request->input('deleted_image_ids');
            if (is_string($deletedIds)) {
                $deletedIds = json_decode($deletedIds, true) ?? explode(',', $deletedIds);
            }
            if (is_array($deletedIds)) {
                $imagesToDelete = ProductImage::where('product_id', $product->id)
                    ->whereIn('id', $deletedIds)
                    ->get();

                foreach ($imagesToDelete as $imgToDelete) {
                    if (str_starts_with($imgToDelete->image_path, 'uploads/')) {
                        $filePath = public_path($imgToDelete->image_path);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                    $imgToDelete->delete();
                }
            }
        }

        // 2. Upload newly added files
        $uploadedFiles = $request->file('images') ?? [];
        $newImageModels = [];

        foreach ($uploadedFiles as $idx => $file) {
            $filename = 'prod_'.$product->id.'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);

            $newImg = ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'uploads/products/'.$filename,
                'alt_text' => $product->name,
                'sort_order' => 999 + $idx,
                'is_cover' => false,
            ]);

            $newImageModels[$idx] = $newImg;
            $newImageModels['new_'.$idx] = $newImg;
        }

        // 3. Reorder both existing and newly uploaded images
        $orderData = $request->input('image_order');
        if ($orderData) {
            $orderedItems = is_string($orderData) ? json_decode($orderData, true) : $orderData;
            if (is_array($orderedItems) && ! empty($orderedItems)) {
                $currentPosition = 0;
                foreach ($orderedItems as $itemRef) {
                    $itemRef = trim((string) $itemRef);
                    if (str_starts_with($itemRef, 'existing_') || (is_numeric($itemRef) && ! str_starts_with($itemRef, 'new_'))) {
                        $existingId = (int) str_replace('existing_', '', $itemRef);
                        ProductImage::where('id', $existingId)
                            ->where('product_id', $product->id)
                            ->update([
                                'sort_order' => $currentPosition,
                                'is_cover' => $currentPosition === 0,
                            ]);
                        $currentPosition++;
                    } elseif (str_starts_with($itemRef, 'new_') || isset($newImageModels[$itemRef])) {
                        if (isset($newImageModels[$itemRef])) {
                            $newImageModels[$itemRef]->update([
                                'sort_order' => $currentPosition,
                                'is_cover' => $currentPosition === 0,
                            ]);
                            $currentPosition++;
                        }
                    }
                }
            }
        }

        // 4. Ensure at least one image is marked as cover
        $allImages = $product->images()->orderBy('sort_order', 'asc')->get();
        if ($allImages->isNotEmpty()) {
            $hasCover = $allImages->contains('is_cover', true);
            if (! $hasCover) {
                $first = $allImages->first();
                $first->update(['is_cover' => true]);
            }
        } elseif ($product->images()->count() === 0) {
            // Restore fallback placeholder if all were removed
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'images/placeholder-product.jpg',
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_cover' => true,
            ]);
        }

        // 5. Handle Variants Sync (Tallas & Colores)
        $submittedVariants = $request->input('variants', []);
        $keptVariantIds = [];

        if (is_array($submittedVariants)) {
            foreach ($submittedVariants as $vData) {
                if (! empty($vData['name'])) {
                    $variantAttributes = [
                        'variant_type' => $vData['variant_type'] ?? 'talla',
                        'name' => $vData['name'],
                        'value' => $vData['value'] ?? $vData['name'],
                        'price_modifier' => ! empty($vData['price_modifier']) ? (float) $vData['price_modifier'] : 0.00,
                        'stock_quantity' => isset($vData['stock_quantity']) ? (int) $vData['stock_quantity'] : 10,
                        'is_active' => isset($vData['is_active']) ? (bool) $vData['is_active'] : true,
                    ];

                    if (! empty($vData['id']) && $existingVar = ProductVariant::where('id', $vData['id'])->where('product_id', $product->id)->first()) {
                        $existingVar->update($variantAttributes);
                        $keptVariantIds[] = $existingVar->id;
                    } else {
                        $newVar = ProductVariant::create(array_merge($variantAttributes, [
                            'product_id' => $product->id,
                        ]));
                        $keptVariantIds[] = $newVar->id;
                    }
                }
            }
        }

        // Remove deleted variants
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $keptVariantIds)
            ->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto, imágenes y variantes actualizados correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Delete uploaded files
        foreach ($product->images as $img) {
            if (str_starts_with($img->image_path, 'uploads/')) {
                $filePath = public_path($img->image_path);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }
}
