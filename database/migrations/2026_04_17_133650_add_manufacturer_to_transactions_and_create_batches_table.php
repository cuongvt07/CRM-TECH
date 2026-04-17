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
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->string('manufacturer_name')->nullable()->after('partner_name');
        });

        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null');
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['product_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('manufacturer_name');
        });
    }
};
