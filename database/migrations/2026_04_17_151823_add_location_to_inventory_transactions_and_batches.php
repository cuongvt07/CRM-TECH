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
            $table->string('location')->nullable()->after('batch_number');
        });

        Schema::table('inventory_batches', function (Blueprint $table) {
            $table->string('location')->nullable()->after('batch_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        Schema::table('inventory_batches', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
