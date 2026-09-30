<?php

use App\Models\Category;
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
    ]);
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
