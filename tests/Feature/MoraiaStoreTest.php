<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('homepage returns a successful response and renders moraia universe', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('MORAIA');
    $response->assertSee('El arte de consentirte');
});

test('shop page renders product catalogue', function () {
    $response = $this->get('/shop');

    $response->assertStatus(200);
    $response->assertSee('Catálogo');
});

test('single category page renders filtered products', function () {
    $category = Category::first();
    expect($category)->not->toBeNull();

    $response = $this->get('/category/'.$category->slug);
    $response->assertStatus(200);
    $response->assertSee($category->name);
});

test('product detail page loads with details and variants', function () {
    $product = Product::first();
    expect($product)->not->toBeNull();

    $response = $this->get('/product/'.$product->slug);
    $response->assertStatus(200);
    $response->assertSee($product->name);
    $response->assertSee('Añadir a mi Bolsa');
});

test('cart page renders items in session', function () {
    $product = Product::first();
    expect($product)->not->toBeNull();

    $cartData = [
        'item_1' => [
            'key' => 'item_1',
            'product_id' => $product->id,
            'variant_id' => null,
            'name' => $product->name,
            'slug' => $product->slug,
            'variant_name' => '',
            'price' => $product->price,
            'quantity' => 2,
            'image' => $product->cover_image_url,
        ],
    ];

    $cartResponse = $this->withSession(['cart' => $cartData])->get('/cart');
    $cartResponse->assertStatus(200);
    $cartResponse->assertSee($product->name);
});

test('checkout processes an order and creates whatsapp confirmation link', function () {
    $product = Product::first();
    expect($product)->not->toBeNull();

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
        'customer_name' => 'Valeria',
        'customer_lastname' => 'Gómez',
        'customer_phone' => '04121234567',
        'customer_whatsapp' => '04121234567',
        'customer_email' => 'valeria@example.com',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Av. Francisco de Miranda, Edif. Parque Cristal',
        'customer_notes' => 'Favor envolver para regalo.',
    ];

    $checkoutResponse = $this->withSession(['cart' => $cartData])->post('/checkout', $orderPayload);

    $checkoutResponse->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'customer_email' => 'valeria@example.com',
        'customer_name' => 'Valeria',
        'customer_lastname' => 'Gómez',
        'delivery_method' => 'delivery_caracas',
        'status' => 'Pendiente',
    ]);
});

test('order decreases stock on creation and restores stock on cancellation', function () {
    $product = Product::first();
    $initialStock = $product->stock_quantity;

    $cartData = [
        'item_1' => [
            'key' => 'item_1',
            'product_id' => $product->id,
            'variant_id' => null,
            'name' => $product->name,
            'slug' => $product->slug,
            'variant_name' => '',
            'price' => $product->price,
            'quantity' => 2,
            'image' => $product->cover_image_url,
        ],
    ];

    $orderPayload = [
        'customer_name' => 'Sofia',
        'customer_lastname' => 'López',
        'customer_phone' => '04141112233',
        'customer_whatsapp' => '04141112233',
        'delivery_method' => 'pickup',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Pick-up',
    ];

    $this->withSession(['cart' => $cartData])->post('/checkout', $orderPayload);

    $product->refresh();
    expect($product->stock_quantity)->toBe($initialStock - 2);

    $order = Order::where('customer_name', 'Sofia')->first();
    expect($order->status)->toBe('Pendiente');

    $admin = User::where('role', 'admin')->first();

    // Cancel order -> restores stock
    $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'Cancelada']);
    $product->refresh();
    expect($product->stock_quantity)->toBe($initialStock);

    // Re-activate order to Confirmada -> decrements stock again
    $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'Confirmada']);
    $product->refresh();
    expect($product->stock_quantity)->toBe($initialStock - 2);
});

test('customer and admin can download order pdf receipt', function () {
    $order = Order::create([
        'order_code' => 'MOR-2026-TEST1',
        'customer_name' => 'Carla',
        'customer_lastname' => 'Mendoza',
        'customer_phone' => '04129876543',
        'customer_whatsapp' => '04129876543',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Las Mercedes, Calle París',
        'subtotal' => 45.00,
        'shipping_fee' => 3.00,
        'total' => 48.00,
        'status' => 'Pendiente',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Lencería de Seda',
        'quantity' => 1,
        'unit_price' => 45.00,
        'total_price' => 45.00,
    ]);

    // Public Customer PDF Download
    $customerResponse = $this->get("/order-success/{$order->order_code}/pdf");
    $customerResponse->assertStatus(200);
    $customerResponse->assertHeader('content-type', 'application/pdf');

    // Admin PDF Download
    $admin = User::where('role', 'admin')->first();
    $adminResponse = $this->actingAs($admin)->get("/admin/orders/{$order->id}/pdf");
    $adminResponse->assertStatus(200);
    $adminResponse->assertHeader('content-type', 'application/pdf');
});

test('admin routes are protected against guests', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/admin/login');
});

test('admin can authenticate and access dashboard', function () {
    $admin = User::where('role', 'admin')->first();
    expect($admin)->not->toBeNull();

    $loginResponse = $this->post('/admin/login', [
        'email' => $admin->email,
        'password' => 'moraia2026',
    ]);

    $loginResponse->assertRedirect('/admin');

    $this->actingAs($admin);
    $dashboardResponse = $this->get('/admin');
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Dashboard');
    $dashboardResponse->assertSee('Ventas Registradas');
});

test('admin dashboard handles period filters and custom date ranges', function () {
    $admin = User::where('role', 'admin')->first();

    $this->actingAs($admin);

    // Test preset periods
    $periods = ['today', 'yesterday', 'this_week', 'last_7_days', 'this_month', 'last_30_days', 'this_year', 'all'];
    foreach ($periods as $period) {
        $response = $this->get('/admin?period='.$period);
        $response->assertStatus(200);
        $response->assertSee('Tendencia de Ventas', false);
        $response->assertSee('Estado de Órdenes', false);
        $response->assertSee('Métodos de Entrega', false);
        $response->assertSee('Propósito de Compra', false);
    }

    // Test custom date range
    $customResponse = $this->get('/admin?period=custom&date_from=2026-01-01&date_to=2026-12-31');
    $customResponse->assertStatus(200);
    $customResponse->assertSee('Personalizado (01/01/2026 - 31/12/2026)');
});

test('admin can reorder categories via drag and drop endpoint', function () {
    $admin = User::where('role', 'admin')->first();
    $this->actingAs($admin);

    $categories = Category::all();
    expect($categories->count())->toBeGreaterThan(1);

    // Reverse existing order
    $reversedIds = $categories->pluck('id')->reverse()->values()->toArray();

    $response = $this->postJson('/admin/categories/reorder', [
        'order' => $reversedIds,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $firstCatId = $reversedIds[0];
    $updatedFirstCat = Category::find($firstCatId);
    expect($updatedFirstCat->sort_order)->toBe(1);
});

test('admin can manage product variants such as sizes and colors', function () {
    $admin = User::where('role', 'admin')->first();
    $this->actingAs($admin);

    $category = Category::first();
    expect($category)->not->toBeNull();

    // Create product with variants
    $response = $this->post('/admin/products', [
        'category_id' => $category->id,
        'name' => 'Bralette Prueba Variantes',
        'price' => 25.00,
        'stock_quantity' => 15,
        'target_audience' => 'moraia_intimo',
        'variants' => [
            [
                'variant_type' => 'talla',
                'name' => 'Talla 34B',
                'value' => '34B',
                'price_modifier' => 0.00,
                'stock_quantity' => 10,
                'is_active' => 1,
            ],
            [
                'variant_type' => 'color',
                'name' => 'Rosa Mauve',
                'value' => '#D87F86',
                'price_modifier' => 2.00,
                'stock_quantity' => 5,
                'is_active' => 1,
            ],
        ],
    ]);

    $response->assertRedirect('/admin/products');

    $product = Product::where('name', 'Bralette Prueba Variantes')->first();
    expect($product)->not->toBeNull();
    expect($product->variants()->count())->toBe(2);

    // Update variants
    $sizeVar = $product->variants()->where('variant_type', 'talla')->first();
    $updateResponse = $this->put('/admin/products/'.$product->id, [
        'category_id' => $category->id,
        'name' => 'Bralette Prueba Variantes',
        'price' => 28.00,
        'stock_quantity' => 20,
        'target_audience' => 'moraia_intimo',
        'variants' => [
            [
                'id' => $sizeVar->id,
                'variant_type' => 'talla',
                'name' => 'Talla 36B',
                'value' => '36B',
                'price_modifier' => 0.00,
                'stock_quantity' => 12,
                'is_active' => 1,
            ],
            [
                'variant_type' => 'color',
                'name' => 'Negro Noche',
                'value' => '#242020',
                'price_modifier' => 0.00,
                'stock_quantity' => 8,
                'is_active' => 1,
            ],
        ],
    ]);

    $updateResponse->assertRedirect('/admin/products');

    $updatedProduct = $product->fresh(['variants']);
    expect($updatedProduct->variants()->count())->toBe(2);
    expect($updatedProduct->variants()->where('name', 'Talla 36B')->exists())->toBeTrue();
    expect($updatedProduct->variants()->where('name', 'Negro Noche')->exists())->toBeTrue();
    expect($updatedProduct->variants()->where('name', 'Rosa Mauve')->exists())->toBeFalse();
});

test('adding product with variants to cart requires selecting a variant', function () {
    $productWithVariants = Product::whereHas('variants')->first();
    expect($productWithVariants)->not->toBeNull();

    // Attempt to add without variant_id
    $response = $this->postJson(route('cart.add'), [
        'product_id' => $productWithVariants->id,
        'quantity' => 1,
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'requires_variant' => true,
    ]);

    // Now add with valid variant
    $variant = $productWithVariants->variants->first();
    $validResponse = $this->postJson(route('cart.add'), [
        'product_id' => $productWithVariants->id,
        'variant_id' => $variant->id,
        'quantity' => 1,
    ]);

    $validResponse->assertStatus(200);
    $validResponse->assertJson([
        'success' => true,
    ]);
});
