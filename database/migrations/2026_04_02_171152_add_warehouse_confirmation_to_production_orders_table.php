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
        Schema::table('production_orders', function (Blueprint $table) {
            $table->string('warehouse_status')->default('pending')->comment('pending, sufficient, insufficient, pending_production, delivering');
            $table->text('warehouse_note')->nullable();
            $table->foreignId('warehouse_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('warehouse_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropForeign(['warehouse_confirmed_by']);
            $table->dropColumn(['warehouse_status', 'warehouse_note', 'warehouse_confirmed_by', 'warehouse_confirmed_at']);
        });
    }
};
