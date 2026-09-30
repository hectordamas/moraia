<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\ViewErrorBag;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$views = [
    'pages.home' => ['categories' => Category::all(), 'featuredProducts' => Product::all(), 'giftProducts' => Product::all(), 'manifesto' => 'test'],
    'pages.shop' => ['products' => Product::paginate(12), 'categories' => Category::all(), 'activeCategory' => null, 'search' => '', 'sort' => 'newest', 'line' => ''],
    'pages.about' => ['manifesto' => 'test'],
    'pages.contact' => ['phone' => '+584120206548', 'email' => 'By.moraia@gmail.com', 'instagram' => '@by.moraia'],
    'pages.cart' => ['cart' => [], 'subtotal' => 0],
    'pages.checkout' => ['cart' => [], 'subtotal' => 0, 'shippingCost' => 3, 'freeShippingThreshold' => 50, 'total' => 3, 'errors' => new ViewErrorBag],
    'pages.privacy' => [],
    'pages.terms' => [],
    'pages.shipping-returns' => [],
];

foreach ($views as $name => $data) {
    try {
        $output = view($name, $data)->render();
        echo "PASS: {$name} (len: ".strlen($output).")\n";
    } catch (Throwable $e) {
        echo "FAIL: {$name} -> ".$e->getMessage().' in '.$e->getFile().':'.$e->getLine()."\n";
    }
}
