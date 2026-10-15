<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Services\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductImageService $imageService
    ) {}

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
            'options_config' => 'nullable',
            'customizations_config' => 'nullable',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'target_audience' => 'required|string|in:all,moraia,moraia_intimo',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'images.*' => ['nullable', 'image', 'max:12288'],
            'image_crops' => 'nullable',
            'image_file_keys' => 'nullable',
            'image_order' => 'nullable',
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string|max:150',
            'variants.*.variant_type' => 'nullable|string|max:50',
            'variants.*.value' => 'nullable|string|max:255',
            'variants.*.options' => 'nullable',
            'variants.*.sku' => 'nullable|string|max:60',
            'variants.*.selection_type' => 'nullable|string|in:single,multiple',
            'variants.*.price_modifier' => 'nullable|numeric',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($validated['name']);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-'.($count + 1);
        }

        $optionsConfig = $request->filled('options_config')
            ? (is_string($request->input('options_config')) ? json_decode($request->input('options_config'), true) : $request->input('options_config'))
            : null;

        $customizationsConfig = $request->filled('customizations_config')
            ? (is_string($request->input('customizations_config')) ? json_decode($request->input('customizations_config'), true) : $request->input('customizations_config'))
            : null;

        // Calculate total stock if matrix variants are supplied
        $submittedVariants = $request->input('variants', []);
        $totalStock = (int) $validated['stock_quantity'];
        if (is_array($submittedVariants) && count($submittedVariants) > 0) {
            $combinationVariants = array_filter($submittedVariants, fn ($v) => ! empty($v['name']) && (! isset($v['variant_type']) || $v['variant_type'] === 'combinacion' || $v['variant_type'] === 'talla' || $v['variant_type'] === 'color'));
            if (count($combinationVariants) > 0) {
                $totalStock = array_sum(array_map(fn ($v) => (int) ($v['stock_quantity'] ?? 0), $combinationVariants));
            }
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $validated['sku'] ?? 'MOR-'.Str::upper(Str::random(6)),
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock_quantity' => $totalStock,
            'options_config' => $optionsConfig,
            'customizations_config' => $customizationsConfig,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'badge' => $validated['badge'] ?? null,
            'target_audience' => $validated['target_audience'],
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? ($validated['short_description'] ?? null),
        ]);

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images');
            $newFileKeys = $request->filled('image_file_keys')
                ? (is_string($request->input('image_file_keys')) ? json_decode($request->input('image_file_keys'), true) : $request->input('image_file_keys'))
                : [];
            $cropsRegistry = $request->filled('image_crops')
                ? (is_string($request->input('image_crops')) ? json_decode($request->input('image_crops'), true) : $request->input('image_crops'))
                : [];

            // Sort files according to image_order if provided
            $orderData = $request->input('image_order');
            if ($orderData) {
                $orderIndexes = is_string($orderData) ? json_decode($orderData, true) : $orderData;
                if (is_array($orderIndexes) && ! empty($orderIndexes)) {
                    $sortedFiles = [];
                    $sortedKeys = [];
                    foreach ($orderIndexes as $idxKey) {
                        $foundIdx = null;
                        if (! empty($newFileKeys)) {
                            $foundIdx = array_search($idxKey, $newFileKeys, true);
                        }
                        if ($foundIdx === false || $foundIdx === null) {
                            $numericIdx = is_numeric($idxKey) ? (int) $idxKey : (int) str_replace('new_', '', $idxKey);
                            if (isset($uploadedFiles[$numericIdx])) {
                                $foundIdx = $numericIdx;
                            }
                        }

                        if ($foundIdx !== false && $foundIdx !== null && isset($uploadedFiles[$foundIdx])) {
                            $sortedFiles[] = $uploadedFiles[$foundIdx];
                            $sortedKeys[] = $idxKey;
                            unset($uploadedFiles[$foundIdx]);
                        }
                    }
                    foreach ($uploadedFiles as $remIdx => $remaining) {
                        $sortedFiles[] = $remaining;
                        $sortedKeys[] = $newFileKeys[$remIdx] ?? ('new_'.$remIdx);
                    }
                    $uploadedFiles = $sortedFiles;
                    $newFileKeys = $sortedKeys;
                }
            }

            foreach ($uploadedFiles as $idx => $file) {
                $cardId = $newFileKeys[$idx] ?? ('new_'.$idx);
                $cropData = $cropsRegistry[$cardId] ?? null;

                $this->imageService->storeImage(
                    product: $product,
                    file: $file,
                    cropData: $cropData,
                    sortOrder: $idx,
                    isCover: $idx === 0
                );
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

        // Handle Variants (Combinaciones & Opciones de Personalización)
        if (is_array($submittedVariants)) {
            foreach ($submittedVariants as $vData) {
                if (! empty($vData['name'])) {
                    $parsedOptions = null;
                    if (! empty($vData['options'])) {
                        $parsedOptions = is_string($vData['options']) ? json_decode($vData['options'], true) : $vData['options'];
                    }

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_type' => $vData['variant_type'] ?? 'combinacion',
                        'name' => $vData['name'],
                        'value' => $vData['value'] ?? $vData['name'],
                        'options' => $parsedOptions,
                        'sku' => $vData['sku'] ?? null,
                        'selection_type' => $vData['selection_type'] ?? 'single',
                        'price_modifier' => ! empty($vData['price_modifier']) ? (float) $vData['price_modifier'] : 0.00,
                        'stock_quantity' => isset($vData['stock_quantity']) ? (int) $vData['stock_quantity'] : 10,
                        'is_active' => isset($vData['is_active']) ? (bool) $vData['is_active'] : true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.edit', $product->id)->with('success', '¡Producto creado exitosamente! Ahora puedes continuar configurando sus detalles o variantes.');
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
            'options_config' => 'nullable',
            'customizations_config' => 'nullable',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'target_audience' => 'required|string|in:all,moraia,moraia_intimo',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'images.*' => ['nullable', 'image', 'max:12288'],
            'image_crops' => 'nullable',
            'image_file_keys' => 'nullable',
            'image_order' => 'nullable',
            'deleted_image_ids' => 'nullable',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer',
            'variants.*.name' => 'nullable|string|max:150',
            'variants.*.variant_type' => 'nullable|string|max:50',
            'variants.*.value' => 'nullable|string|max:255',
            'variants.*.options' => 'nullable',
            'variants.*.sku' => 'nullable|string|max:60',
            'variants.*.selection_type' => 'nullable|string|in:single,multiple',
            'variants.*.price_modifier' => 'nullable|numeric',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
        ]);

        $optionsConfig = $request->filled('options_config')
            ? (is_string($request->input('options_config')) ? json_decode($request->input('options_config'), true) : $request->input('options_config'))
            : null;

        $customizationsConfig = $request->filled('customizations_config')
            ? (is_string($request->input('customizations_config')) ? json_decode($request->input('customizations_config'), true) : $request->input('customizations_config'))
            : null;

        // Calculate total stock if matrix variants are supplied
        $submittedVariants = $request->input('variants', []);
        $totalStock = (int) $validated['stock_quantity'];
        if (is_array($submittedVariants) && count($submittedVariants) > 0) {
            $combinationVariants = array_filter($submittedVariants, fn ($v) => ! empty($v['name']) && (! isset($v['variant_type']) || $v['variant_type'] === 'combinacion' || $v['variant_type'] === 'talla' || $v['variant_type'] === 'color'));
            if (count($combinationVariants) > 0) {
                $totalStock = array_sum(array_map(fn ($v) => (int) ($v['stock_quantity'] ?? 0), $combinationVariants));
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?? $product->sku,
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'stock_quantity' => $totalStock,
            'options_config' => $optionsConfig,
            'customizations_config' => $customizationsConfig,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
            'is_featured' => ! empty($validated['is_featured']),
            'badge' => $validated['badge'] ?? null,
            'target_audience' => $validated['target_audience'],
            'seo_title' => $validated['seo_title'] ?? $validated['name'].' | Moraia',
            'seo_description' => $validated['seo_description'] ?? ($validated['short_description'] ?? null),
        ]);

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
                    $this->imageService->deleteImageFiles($imgToDelete);
                    $imgToDelete->delete();
                }
            }
        }

        // 2. Upload newly added files
        $uploadedFiles = $request->file('images') ?? [];
        $newFileKeys = $request->filled('image_file_keys')
            ? (is_string($request->input('image_file_keys')) ? json_decode($request->input('image_file_keys'), true) : $request->input('image_file_keys'))
            : [];
        $cropsRegistry = $request->filled('image_crops')
            ? (is_string($request->input('image_crops')) ? json_decode($request->input('image_crops'), true) : $request->input('image_crops'))
            : [];

        $newImageModels = [];

        foreach ($uploadedFiles as $idx => $file) {
            $cardId = $newFileKeys[$idx] ?? ('new_'.$idx);
            $cropData = $cropsRegistry[$cardId] ?? null;

            $newImg = $this->imageService->storeImage(
                product: $product,
                file: $file,
                cropData: $cropData,
                sortOrder: 999 + $idx,
                isCover: false
            );

            $newImageModels[$cardId] = $newImg;
            $newImageModels['new_'.$idx] = $newImg;
            $newImageModels[$idx] = $newImg;
        }

        // 3. Handle re-cropping of existing images
        if (! empty($cropsRegistry)) {
            foreach ($cropsRegistry as $key => $crop) {
                if (str_starts_with((string) $key, 'existing_')) {
                    $existingId = (int) str_replace('existing_', '', (string) $key);
                    $existingImg = ProductImage::where('id', $existingId)
                        ->where('product_id', $product->id)
                        ->first();

                    if ($existingImg && is_array($crop)) {
                        $this->imageService->updateCrop($existingImg, $crop);
                    }
                }
            }
        }

        // 4. Reorder both existing and newly uploaded images
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
                    } elseif (isset($newImageModels[$itemRef])) {
                        $newImageModels[$itemRef]->update([
                            'sort_order' => $currentPosition,
                            'is_cover' => $currentPosition === 0,
                        ]);
                        $currentPosition++;
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

        // 5. Handle Variants Sync (Combinaciones & Opciones)
        $keptVariantIds = [];

        if (is_array($submittedVariants)) {
            foreach ($submittedVariants as $vData) {
                if (! empty($vData['name'])) {
                    $parsedOptions = null;
                    if (! empty($vData['options'])) {
                        $parsedOptions = is_string($vData['options']) ? json_decode($vData['options'], true) : $vData['options'];
                    }

                    $variantAttributes = [
                        'variant_type' => $vData['variant_type'] ?? 'combinacion',
                        'name' => $vData['name'],
                        'value' => $vData['value'] ?? $vData['name'],
                        'options' => $parsedOptions,
                        'sku' => $vData['sku'] ?? null,
                        'selection_type' => $vData['selection_type'] ?? 'single',
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

        return redirect()->route('admin.products.edit', $product->id)->with('success', '¡Producto, imágenes y variantes actualizados correctamente!');
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
