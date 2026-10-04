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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('packaging_id')->nullable()->after('gift_card_message')->constrained('packagings')->nullOnDelete();
            $table->string('packaging_name')->nullable()->after('packaging_id');
            $table->decimal('packaging_price', 10, 2)->default(0.00)->after('packaging_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['packaging_id']);
            $table->dropColumn(['packaging_id', 'packaging_name', 'packaging_price']);
        });
    }
};
