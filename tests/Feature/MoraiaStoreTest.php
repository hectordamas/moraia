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
