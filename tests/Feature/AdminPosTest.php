<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->admin = User::where('role', 'admin')->first();
});

test('admin can access pos point of sale page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.pos.index'));

    $response->assertStatus(200);
    $response->assertSee('Punto de Venta (POS)');
    $response->assertSee('Ticket de Venta');
});

test('admin can create manual order through pos store', function () {
    $category = Category::first();
    $product = Product::first();

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'variant_type' => 'talla',
        'name' => 'M Especial',
        'value' => 'M',
        'price_modifier' => 0.00,
        'stock_quantity' => 10,
        'is_active' => true,
    ]);

    $payload = [
        'customer_name' => 'María',
        'customer_lastname' => 'Pérez',
        'customer_whatsapp' => '04141234567',
        'customer_phone' => '04141234567',
        'customer_email' => 'maria@example.com',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Av. Francisco de Miranda, Edif Los Ruices',
        'status' => 'Confirmada',
        'shipping_fee' => 3.00,
        'discount_amount' => 5.00,
        'items' => [
            [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'quantity' => 2,
                'unit_price' => 45.00,
            ],
        ],
    ];

    $response = $this->actingAs($this->admin)->postJson(route('admin.pos.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $order = Order::where('customer_whatsapp', '04141234567')->first();
    expect($order)->not->toBeNull();
    expect((float) $order->subtotal)->toEqual(90.00);
    expect((float) $order->shipping_fee)->toEqual(3.00);
    expect((float) $order->total)->toEqual(88.00); // 90 - 5 + 3 = 88
    expect($order->items)->toHaveCount(1);
    expect($order->status)->toBe('Confirmada');

    // Variant stock decremented: 10 - 2 = 8
    $variant->refresh();
    expect($variant->stock_quantity)->toBe(8);
});

test('admin dashboard metrics reflect only delivered orders while recent orders show all statuses', function () {
    Order::query()->delete();

    // Create a pending order of $50
    Order::create([
        'order_code' => 'MOR-PENDING',
        'customer_name' => 'Pendiente',
        'customer_lastname' => 'User',
        'customer_phone' => '04120000001',
        'customer_whatsapp' => '04120000001',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Caracas',
        'subtotal' => 50.00,
        'shipping_fee' => 0.00,
        'total' => 50.00,
        'status' => 'Pendiente',
    ]);

    // Create a delivered order of $100
    Order::create([
        'order_code' => 'MOR-DELIVERED',
        'customer_name' => 'Entregada',
        'customer_lastname' => 'User',
        'customer_phone' => '04120000002',
        'customer_whatsapp' => '04120000002',
        'delivery_method' => 'delivery_caracas',
        'delivery_city' => 'Caracas',
        'delivery_address' => 'Caracas',
        'subtotal' => 100.00,
        'shipping_fee' => 0.00,
        'total' => 100.00,
        'status' => 'Entregada',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.dashboard', ['period' => 'this_month']));

    $response->assertStatus(200);
    // Total revenue should only include delivered order ($100.00)
    $response->assertViewHas('totalRevenue', 100.00);
    // Total orders metric should only include delivered order (1)
    $response->assertViewHas('totalOrders', 1);
    // Ticket promedio should be $100.00
    $response->assertViewHas('averageOrderValue', 100.00);
    // Pending orders count should still detect the pending order (1)
    $response->assertViewHas('pendingOrdersCount', 1);
    // Delivered orders count (1)
    $response->assertViewHas('deliveredOrdersCount', 1);
    // Recent orders table should contain both orders (2)
    $recentOrders = $response->viewData('recentOrders');
    expect($recentOrders)->toHaveCount(2);
});
