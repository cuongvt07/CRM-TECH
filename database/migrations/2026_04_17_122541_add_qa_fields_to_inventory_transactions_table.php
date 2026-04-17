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
            $table->enum('qa_inspection_status', ['pending', 'inspecting'])->default('pending');
            $table->enum('qa_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('qa_note')->nullable();
            $table->foreignId('qa_inspector_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['qa_inspector_id']);
            $table->dropColumn(['qa_inspection_status', 'qa_status', 'qa_note', 'qa_inspector_id']);
        });
    }
};
