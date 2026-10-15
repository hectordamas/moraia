<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductImageService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->admin = User::where('role', 'admin')->first();
});

test('admin can upload rectangular image of any aspect ratio and original is preserved with 1:1 crop', function () {
    $category = Category::first();

    // Create a non-square 1200x800 landscape image
    $fakeImage = UploadedFile::fake()->image('landscape_photo.jpg', 1200, 800);

    $payload = [
        'category_id' => $category->id,
        'name' => 'Vestido Seda Multiformato',
        'price' => 55.00,
        'stock_quantity' => 20,
        'target_audience' => 'all',
        'images' => [$fakeImage],
        'image_order' => json_encode(['new_0']),
    ];

    $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

    $response->assertRedirect();

    $product = Product::where('name', 'Vestido Seda Multiformato')->first();
    expect($product)->not->toBeNull();

    $images = $product->images;
    expect($images)->toHaveCount(1);

    $img = $images->first();
    expect($img->image_path)->not->toBeNull()
        ->and($img->original_path)->not->toBeNull()
        ->and($img->is_cover)->toBeTrue();

    // Check files exist on disk
    $croppedPath = public_path($img->image_path);
    $originalPath = public_path($img->original_path);

    expect(file_exists($croppedPath))->toBeTrue()
        ->and(file_exists($originalPath))->toBeTrue();

    // Check cropped image is 1:1 aspect ratio
    $croppedSize = getimagesize($croppedPath);
    expect($croppedSize[0])->toBe($croppedSize[1]); // width == height (1:1)

    // Cleanup generated files
    @unlink($croppedPath);
    @unlink($originalPath);
});

test('admin can upload multiple images with custom crop coordinates and sort order', function () {
    $category = Category::first();

    $file1 = UploadedFile::fake()->image('photo_one.jpg', 1600, 900);
    $file2 = UploadedFile::fake()->image('photo_two.jpg', 800, 1200);

    $cropData = [
        'new_0' => ['x' => 100, 'y' => 50, 'width' => 700, 'height' => 700],
        'new_1' => ['x' => 0, 'y' => 200, 'width' => 800, 'height' => 800],
    ];

    $payload = [
        'category_id' => $category->id,
        'name' => 'Conjunto Encaje Romance',
        'price' => 42.00,
        'stock_quantity' => 15,
        'target_audience' => 'moraia_intimo',
        'images' => [$file1, $file2],
        'image_file_keys' => json_encode(['new_0', 'new_1']),
        'image_crops' => json_encode($cropData),
        'image_order' => json_encode(['new_1', 'new_0']), // inverted order, file2 as cover
    ];

    $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);
    $response->assertRedirect();

    $product = Product::where('name', 'Conjunto Encaje Romance')->first();
    $images = $product->images()->orderBy('sort_order', 'asc')->get();

    expect($images)->toHaveCount(2);

    $cover = $images->first();
    expect($cover->is_cover)->toBeTrue();

    // Cleanup files
    foreach ($images as $img) {
        if ($img->image_path && file_exists(public_path($img->image_path))) {
            @unlink(public_path($img->image_path));
        }
        if ($img->original_path && file_exists(public_path($img->original_path))) {
            @unlink(public_path($img->original_path));
        }
    }
});

test('admin can re-crop existing image in product edit', function () {
    $category = Category::first();
    $fakeImage = UploadedFile::fake()->image('initial_pic.jpg', 1000, 600);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Producto Para Recorte',
        'slug' => 'producto-para-recorte',
        'sku' => 'REC-001',
        'price' => 30.00,
        'stock_quantity' => 10,
        'target_audience' => 'all',
    ]);

    $service = app(ProductImageService::class);
    $storedImg = $service->storeImage($product, $fakeImage, null, 0, true);

    $oldCroppedPath = public_path($storedImg->image_path);
    expect(file_exists($oldCroppedPath))->toBeTrue();

    // Re-crop with new coordinates
    $newCrop = [
        'existing_'.$storedImg->id => ['x' => 50, 'y' => 50, 'width' => 500, 'height' => 500],
    ];

    $updatePayload = [
        'category_id' => $category->id,
        'name' => 'Producto Para Recorte Actualizado',
        'price' => 30.00,
        'stock_quantity' => 10,
        'target_audience' => 'all',
        'image_crops' => json_encode($newCrop),
        'image_order' => json_encode(['existing_'.$storedImg->id]),
    ];

    $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product->id), $updatePayload);
    $response->assertRedirect();

    $storedImg->refresh();
    expect($storedImg->crop_data)->not->toBeNull()
        ->and($storedImg->crop_data['x'])->toBe(50);

    // Cleanup
    $service->deleteImageFiles($storedImg);
});
