<?php

use App\Models\Order;
use App\Models\Packaging;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('guests cannot access admin packaging routes', function () {
    $this->get('/admin/packagings')->assertRedirect('/admin/login');
    $this->get('/admin/packagings/create')->assertRedirect('/admin/login');
});

test('admin can view packagings index list', function () {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->get('/admin/packagings');

    $response->assertStatus(200);
    $response->assertSee('Empaques', false);
    $response->assertSee('Bolsa de Satén', false);
    $response->assertSee('Caja Rígida de Lujo Moraia', false);
});

test('admin can create a new packaging with capacity and price', function () {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->post('/admin/packagings', [
        'name' => 'Caja Regalo Edición San Valentín',
        'description' => 'Caja en forma de corazón con lazo rojo y pétalos perfumados',
        'capacity' => 'Hasta 3 prendas íntimas',
        'price' => '4.50',
        'sort_order' => 10,
        'is_active' => '1',
        'is_default' => '0',
    ]);

    $response->assertRedirect('/admin/packagings');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('packagings', [
        'name' => 'Caja Regalo Edición San Valentín',
        'capacity' => 'Hasta 3 prendas íntimas',
        'price' => 4.50,
        'is_active' => 1,
    ]);
});

test('admin can update an existing packaging', function () {
    $admin = User::where('role', 'admin')->first();
    $packaging = Packaging::first();

    $response = $this->actingAs($admin)->put('/admin/packagings/'.$packaging->id, [
        'name' => 'Bolsa de Satén Premium Actualizada',
        'description' => 'Nueva descripción con satén importado',
        'capacity' => '1 a 3 prendas',
        'price' => '1.50',
        'sort_order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect('/admin/packagings');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('packagings', [
        'id' => $packaging->id,
        'name' => 'Bolsa de Satén Premium Actualizada',
        'price' => 1.50,
    ]);
});

test('admin can delete a packaging', function () {
    $admin = User::where('role', 'admin')->first();
    $packaging = Packaging::create([
        'name' => 'Empaque Temporal Para Borrar',
        'capacity' => '1 unidad',
        'price' => 0.00,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->delete('/admin/packagings/'.$packaging->id);

    $response->assertRedirect('/admin/packagings');
    $this->assertDatabaseMissing('packagings', ['id' => $packaging->id]);
});

test('admin can reorder packagings via ajax', function () {
    $admin = User::where('role', 'admin')->first();
    $packagings = Packaging::take(3)->get();
    expect($packagings->count())->toBeGreaterThanOrEqual(2);

    $reversedIds = $packagings->pluck('id')->reverse()->values()->toArray();

    $response = $this->actingAs($admin)->postJson('/admin/packagings/reorder', [
        'order' => $reversedIds,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $firstReordered = Packaging::find($reversedIds[0]);
    expect($firstReordered->sort_order)->toBe(1);
});

test('reordering packagings in admin updates the order displayed on checkout frontend', function () {
    $admin = User::where('role', 'admin')->first();
    $allPackagings = Packaging::active()->get();
    expect($allPackagings->count())->toBeGreaterThanOrEqual(2);

    $originalFirst = $allPackagings->first();
    $originalLast = $allPackagings->last();

    // Reorder: reverse the entire list of packagings
    $reversedIds = $allPackagings->pluck('id')->reverse()->values()->toArray();

    $this->actingAs($admin)->postJson('/admin/packagings/reorder', [
        'order' => $reversedIds,
    ])->assertOk();

    // Check checkout page in frontend
    $product = Product::first();
    $cartData = [
        'item_1' => [
            'key' => 'item_1',
            'product_id' => $product->id,
            'variant_id' => null,
            'name' => $product->name,
            'slug' => $product->slug,
            'variant_name' => '',
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->cover_image_url,
        ],
    ];

    $checkoutResponse = $this->withSession(['cart' => $cartData])->get('/checkout');
    $checkoutResponse->assertStatus(200);

    $viewPackagings = $checkoutResponse->viewData('packagings');
    expect($viewPackagings->first()->id)->toBe($originalLast->id);
    expect($viewPackagings->last()->id)->toBe($originalFirst->id);
});

test('checkout processes packaging selection and includes it in whatsapp url and order details', function () {
    $product = Product::first();
    $packaging = Packaging::where('is_active', true)->first();

    $cartData = [
        'item_1' => [
            'key' => 'item_1',
            'product_id' => $product->id,
            'variant_id' => null,
            'name' => $product->name,
            'slug' => $product->slug,
            'variant_name' => '',
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->cover_image_url,
        ],
    ];

    $orderPayload = [
        'customer_name' => 'Camila',
        'customer_lastname' => 'Mendoza',
        'customer_phone' => '04149876543',
        'customer_whatsapp' => '04149876543',
        'customer_email' => 'camila@example.com',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas - Chacao',
        'delivery_address' => 'Av. San Juan Bosco, Edif. Altamira',
        'packaging_id' => $packaging->id,
        'customer_notes' => 'Tocar timbre 3B.',
    ];

    $response = $this->withSession(['cart' => $cartData])->post('/checkout', $orderPayload);

    $order = Order::where('customer_whatsapp', '04149876543')->latest()->first();
    expect($order)->not->toBeNull();
    expect($order->packaging_name)->toBe($packaging->name);
    expect((float) $order->packaging_price)->toBe((float) $packaging->price);

    $whatsappUrl = $order->generateWhatsAppUrl();
    expect(urldecode($whatsappUrl))->toContain('EMPAQUE');
    expect(urldecode($whatsappUrl))->toContain($packaging->name);

    $response->assertRedirect('/order-success/'.$order->order_code);
});
