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
        Schema::table('products', function (Blueprint $table) {
            $table->json('options_config')->nullable()->after('stock_quantity');
            $table->json('customizations_config')->nullable()->after('options_config');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->json('options')->nullable()->after('value');
            $table->string('sku')->nullable()->after('options');
            $table->string('selection_type')->default('single')->after('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['options_config', 'customizations_config']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['options', 'sku', 'selection_type']);
        });
    }
};
