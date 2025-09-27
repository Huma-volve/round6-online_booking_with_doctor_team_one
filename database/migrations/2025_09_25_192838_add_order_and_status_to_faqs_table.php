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
        Schema::table('faqs', function (Blueprint $table) {
            $table->integer('order')->default(1)->after('answer');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('order');

            // Add indexes for performance
            $table->index('order');
            $table->index('status');
            $table->index(['status', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex(['status', 'order']);
            $table->dropIndex(['status']);
            $table->dropIndex(['order']);
            $table->dropColumn(['order', 'status']);
        });
    }
};
