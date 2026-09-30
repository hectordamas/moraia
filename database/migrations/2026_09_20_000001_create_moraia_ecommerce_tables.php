<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(10);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('badge')->nullable(); // e.g. "Nuevo", "Best Seller", "Exclusivo"
            $table->string('target_audience')->default('all'); // 'moraia', 'moraia_intimo', 'all'
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        // 3. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
        });

        // 4. Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('variant_type'); // 'talla', 'color', 'aroma', 'modelo'
            $table->string('name'); // e.g. "Talla S", "Rosa Pastel", "Vainilla Francesa"
            $table->string('value'); // e.g. "S", "#D87F86", "Vainilla"
            $table->decimal('price_modifier', 10, 2)->default(0.00);
            $table->integer('stock_quantity')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // #MOR-2026-XXXX
            $table->string('customer_name');
            $table->string('customer_lastname');
            $table->string('customer_phone');
            $table->string('customer_whatsapp');
            $table->string('customer_email')->nullable();
            $table->string('delivery_method'); // 'delivery_caracas', 'envio_nacional', 'pickup'
            $table->string('delivery_city');
            $table->text('delivery_address');
            $table->text('customer_notes')->nullable();
            $table->boolean('is_gift')->default(false);
            $table->string('gift_recipient_name')->nullable();
            $table->text('gift_card_message')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('Nueva'); // 'Nueva', 'Confirmada', 'Preparando', 'Lista', 'Entregada', 'Cancelada'
            $table->timestamps();
        });

        // 6. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_image')->nullable();
            $table->string('variant_details')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        // 7. Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('Pendiente'); // 'Pendiente', 'Leído', 'Respondido'
            $table->timestamps();
        });

        // 8. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
